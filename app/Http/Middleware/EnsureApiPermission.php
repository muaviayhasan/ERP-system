<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fail-closed authorization for resourceful API routes.
 *
 * Derives the required ability from the route name ("{resource}.{action}")
 * and verifies the authenticated user holds it. Super-admins are granted
 * everything via the Gate::before bypass in AppServiceProvider.
 *
 * CRUD action → permission verb mapping:
 *   index, show           → view
 *   store                 → create
 *   update                → edit
 *   destroy               → delete
 *
 * If the ability cannot be determined, or the user lacks it, the request is
 * denied (403) — never allowed by default.
 */
class EnsureApiPermission
{
    private const ACTION_MAP = [
        'index' => 'view',
        'show' => 'view',
        'store' => 'create',
        'update' => 'edit',
        'destroy' => 'delete',
        // Domain actions on custom (non-CRUD) routes map to a CRUD verb.
        'calculate' => 'edit',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401, 'Unauthenticated.');
        }

        $routeName = $request->route()?->getName();

        // Fail closed: an unnamed resource route cannot be authorized.
        if (! $routeName || ! str_contains($routeName, '.')) {
            abort(403, 'This action is unauthorized.');
        }

        $resource = substr($routeName, 0, strrpos($routeName, '.'));
        $action = substr($routeName, strrpos($routeName, '.') + 1);

        $verb = self::ACTION_MAP[$action] ?? $action;
        $ability = "{$resource}.{$verb}";

        if (! $user->can($ability)) {
            abort(403, "You do not have permission to perform this action ({$ability}).");
        }

        return $next($request);
    }
}
