<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * When General > Maintenance Mode is on, only users who can manage settings may
 * use the panel; everyone else gets a 503 maintenance page. Logout is always
 * allowed so a blocked user can still sign out.
 */
class EnsureNotInMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('logout')) {
            return $next($request);
        }

        if (Setting::getValue('general', 'maintenance_mode', false)) {
            $user = $request->user();
            if (! $user || ! $user->can('settings.view')) {
                return response()->view('errors.maintenance', [], 503);
            }
        }

        return $next($request);
    }
}
