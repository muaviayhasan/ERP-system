<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Validation\Rules\Password;

/**
 * Builds the password validation rule from the stored Security settings, so the
 * admin-configured policy is enforced wherever passwords are set/changed.
 *
 * Fallback defaults are lenient (min 8, no complexity) — matching the app's
 * prior behaviour — until an admin opts into stronger rules.
 */
class PasswordPolicy
{
    public static function rule(): Password
    {
        $s = Setting::group('security');

        $rule = Password::min((int) ($s['password_min_length'] ?? 8));

        if (! empty($s['password_require_uppercase'])) {
            $rule->mixedCase();
        }
        if (! empty($s['password_require_number'])) {
            $rule->numbers();
        }
        if (! empty($s['password_require_symbol'])) {
            $rule->symbols();
        }

        return $rule;
    }
}
