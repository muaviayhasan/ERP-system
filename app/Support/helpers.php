<?php

use App\Models\Setting;
use Illuminate\Support\Carbon;

if (! function_exists('setting')) {
    /**
     * Read application settings. With only a group, returns the whole group as an
     * array; with a key, returns that single (type-cast) value or $default.
     */
    function setting(string $group, ?string $key = null, mixed $default = null): mixed
    {
        return $key === null
            ? Setting::group($group)
            : Setting::getValue($group, $key, $default);
    }
}

if (! function_exists('format_money')) {
    /** Format an amount using the configured Financial currency settings. */
    function format_money(int|float|string|null $amount): string
    {
        $f = Setting::group('finance');

        $number = number_format(
            (float) $amount,
            (int) ($f['decimal_places'] ?? 2),
            $f['decimal_separator'] ?? '.',
            $f['thousand_separator'] ?? ','
        );
        $symbol = $f['currency_symbol'] ?? '₨';

        return ($f['currency_position'] ?? 'before') === 'after'
            ? "{$number} {$symbol}"
            : "{$symbol} {$number}";
    }
}

if (! function_exists('format_date')) {
    /** Format a date using the configured Localization date_format + timezone. */
    function format_date($date, string $default = ''): string
    {
        if (empty($date)) {
            return $default;
        }

        return Carbon::parse($date)
            ->timezone(config('app.timezone'))
            ->format(setting('localization', 'date_format', 'd/m/Y'));
    }
}

if (! function_exists('format_time')) {
    /** Format a time using the configured Localization time_format (12/24h) + timezone. */
    function format_time($date, string $default = ''): string
    {
        if (empty($date)) {
            return $default;
        }

        $pattern = setting('localization', 'time_format', '12') === '24' ? 'H:i' : 'h:i A';

        return Carbon::parse($date)->timezone(config('app.timezone'))->format($pattern);
    }
}

if (! function_exists('format_datetime')) {
    /** Format a date+time using the configured Localization formats + timezone. */
    function format_datetime($date, string $default = ''): string
    {
        if (empty($date)) {
            return $default;
        }

        $datePattern = setting('localization', 'date_format', 'd/m/Y');
        $timePattern = setting('localization', 'time_format', '12') === '24' ? 'H:i' : 'h:i A';

        return Carbon::parse($date)->timezone(config('app.timezone'))->format("{$datePattern} {$timePattern}");
    }
}

if (! function_exists('per_page')) {
    /** Default pagination size from User Defaults (use for every ->paginate()). */
    function per_page(int $default = 15): int
    {
        return (int) setting('user_defaults', 'items_per_page', $default);
    }
}
