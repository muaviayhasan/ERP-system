<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use App\Support\Audit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Audit-trails successful mutating admin-panel (web) requests to activity_logs,
 * gated by the Security > Audit Logging setting. Validation failures (redirect
 * back with errors) and logout are skipped; secrets are redacted from the payload.
 */
class AuditWebActions
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldLog($request, $response)) {
            $this->record($request);
        }

        return $response;
    }

    private function shouldLog(Request $request, Response $response): bool
    {
        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return false;
        }
        if ($request->routeIs('logout')) {
            return false;
        }
        if ($response->getStatusCode() >= 400) {
            return false;
        }
        // Skip failed validation / errored actions (redirect back with an error bag).
        if ($request->hasSession()) {
            $errors = $request->session()->get('errors');
            if ($errors && $errors->any()) {
                return false;
            }
        }

        return Audit::enabled();
    }

    private function record(Request $request): void
    {
        $user = $request->user();
        [$module, $action] = $this->splitRoute($request->route()?->getName() ?? $request->path());

        try {
            ActivityLog::create([
                'audit_ref' => 'AUD-'.strtoupper(Str::random(10)),
                'user_id' => $user?->id,
                'user_name' => $user?->name,
                'role' => $user?->getRoleNames()->first(),
                'module' => $module,
                'action' => $action,
                'description' => strtoupper($request->method())." {$request->path()}",
                'changes' => Audit::sanitize($request->except(['_token', '_method'])),
                'ip_address' => $request->ip(),
                'device' => substr((string) $request->userAgent(), 0, 255),
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            report($e); // auditing must never break the request
        }
    }

    private function splitRoute(string $routeName): array
    {
        if (str_contains($routeName, '.')) {
            return [
                substr($routeName, 0, strrpos($routeName, '.')),
                substr($routeName, strrpos($routeName, '.') + 1),
            ];
        }

        return [$routeName, 'request'];
    }
}
