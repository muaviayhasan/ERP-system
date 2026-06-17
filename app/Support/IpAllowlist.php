<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\IpUtils;

/**
 * Parses and evaluates the Security > IP Allowlist setting (one IP or CIDR per
 * line; `#` lines ignored). Loopback is always permitted so local/server access
 * can never be locked out.
 */
class IpAllowlist
{
    /** Always-allowed addresses (loopback) — a safety net against total lockout. */
    private const ALWAYS = ['127.0.0.1', '::1'];

    /** @return list<string> normalized entries (trimmed, blanks/comments removed) */
    public static function parse(?string $raw): array
    {
        if ($raw === null || trim($raw) === '') {
            return [];
        }

        return array_values(array_filter(
            array_map('trim', preg_split('/[\r\n,]+/', $raw) ?: []),
            fn (string $line) => $line !== '' && ! str_starts_with($line, '#'),
        ));
    }

    /** True if the list is empty (no restriction) or $ip matches an entry (or loopback). */
    public static function allows(string $ip, array $list): bool
    {
        if ($list === []) {
            return true;
        }

        return IpUtils::checkIp($ip, array_merge($list, self::ALWAYS));
    }
}
