<?php

namespace App\Support;

use App\Models\Setting;

/**
 * Shared audit helpers: whether auditing is enabled (Security setting, default
 * on) and redaction of sensitive values so secrets never reach the audit log.
 */
class Audit
{
    public static function enabled(): bool
    {
        try {
            return (bool) Setting::getValue('security', 'audit_logging', true);
        } catch (\Throwable) {
            return true; // settings unreadable (e.g. fresh install) → keep auditing on
        }
    }

    /** Recursively redact sensitive keys and replace non-scalar values (e.g. files). */
    public static function sanitize(array $input): array
    {
        foreach ($input as $key => $value) {
            if (self::isSensitive((string) $key)) {
                $input[$key] = '***redacted***';
            } elseif (is_array($value)) {
                $input[$key] = self::sanitize($value);
            } elseif (! is_scalar($value) && $value !== null) {
                $input[$key] = '***'.gettype($value).'***';
            }
        }

        return $input;
    }

    private static function isSensitive(string $key): bool
    {
        return (bool) preg_match('/pass|secret|token|api[_-]?key|_key$|credential/i', $key);
    }
}
