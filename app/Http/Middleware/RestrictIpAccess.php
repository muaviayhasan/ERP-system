<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Support\IpAllowlist;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enforces the Security > IP Allowlist. When the list is non-empty, only matching
 * IPs (plus loopback) may use the panel. Logout is always allowed so a blocked
 * user can still sign out. The configuring admin's IP is auto-added at save time
 * (see SettingsController::updateSecurity), so this can't lock you out.
 */
class RestrictIpAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('logout')) {
            return $next($request);
        }

        $list = IpAllowlist::parse(Setting::getValue('security', 'allowed_ips', ''));

        if (! IpAllowlist::allows($request->ip(), $list)) {
            abort(403, 'Access from your IP address is not permitted.');
        }

        return $next($request);
    }
}
