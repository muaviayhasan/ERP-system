<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Writes an audit-trail entry for every successful mutating API request
 * (POST/PUT/PATCH/DELETE → 2xx). Read requests are not logged. Sensitive
 * fields are stripped from the recorded payload so secrets never land in the
 * audit log.
 */
class AuditApiActions
{
    private const SENSITIVE_KEYS = [
        'password', 'password_confirmation', 'current_password',
        'two_factor_secret', 'credentials', 'secret', 'token',
        'api_key', 'apikey', 'access_token', 'client_secret',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldLog($request, $response)) {
            $this->record($request, $response);
        }

        return $response;
    }

    private function shouldLog(Request $request, Response $response): bool
    {
        return in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)
            && $response->getStatusCode() >= 200
            && $response->getStatusCode() < 300;
    }

    private function record(Request $request, Response $response): void
    {
        $user = $request->user();
        $routeName = $request->route()?->getName() ?? $request->path();
        [$module, $action] = $this->splitRoute($routeName);

        try {
            ActivityLog::create([
                'audit_ref' => 'AUD-'.strtoupper(Str::random(10)),
                'user_id' => $user?->id,
                'user_name' => $user?->name,
                'role' => $user?->getRoleNames()->first(),
                'module' => $module,
                'action' => $action,
                'description' => strtoupper($request->method())." {$request->path()}",
                'changes' => $this->sanitize($request->except(self::SENSITIVE_KEYS)),
                'ip_address' => $request->ip(),
                'device' => substr((string) $request->userAgent(), 0, 255),
                'status' => 'success',
            ]);
        } catch (\Throwable $e) {
            // Auditing must never break the request lifecycle.
            report($e);
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

    private function sanitize(array $input): array
    {
        foreach ($input as $key => $value) {
            if (in_array(strtolower((string) $key), self::SENSITIVE_KEYS, true)) {
                $input[$key] = '***redacted***';
            } elseif (is_array($value)) {
                $input[$key] = $this->sanitize($value);
            }
        }

        return $input;
    }
}
