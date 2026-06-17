<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

/**
 * Key-value application settings, grouped by module (general, seo, finance, ...).
 *
 * Reads are cached per group; writes invalidate that group's cache. Values are
 * stored as strings and cast back to their declared `type` on read. Secrets use
 * the "encrypted" type so they are encrypted at rest and never returned in clear
 * text to the UI (the controllers mask them).
 */
class Setting extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [];
    }

    protected static function booted(): void
    {
        static::saved(fn (Setting $s) => Cache::forget(self::cacheKey($s->group)));
        static::deleted(fn (Setting $s) => Cache::forget(self::cacheKey($s->group)));
    }

    private static function cacheKey(string $group): string
    {
        return "settings.group.{$group}";
    }

    /**
     * All settings in a group as key => cast value (cached forever, busted on write).
     * Encrypted secrets are returned decrypted — use only server-side.
     */
    public static function group(string $group): array
    {
        return Cache::rememberForever(self::cacheKey($group), function () use ($group) {
            return static::query()->where('group', $group)->get()
                ->mapWithKeys(fn (Setting $s) => [$s->key => self::castValue($s->value, $s->type)])
                ->all();
        });
    }

    /** Group values overlaid on a defaults array (defaults fill any missing keys). */
    public static function groupWithDefaults(string $group, array $defaults): array
    {
        return array_merge($defaults, static::group($group));
    }

    /** Single value with fallback. */
    public static function getValue(string $group, string $key, mixed $default = null): mixed
    {
        return static::group($group)[$key] ?? $default;
    }

    /** True if a key already has a stored (non-null) value — handy for "keep existing secret". */
    public static function has(string $group, string $key): bool
    {
        return array_key_exists($key, static::group($group)) && static::group($group)[$key] !== null;
    }

    /**
     * Upsert a batch of settings for a group.
     *
     * @param  array<string,mixed>  $values  key => raw value
     * @param  array<string,string>  $types  key => type (string|boolean|integer|float|json|encrypted|text)
     */
    public static function putGroup(string $group, array $values, array $types = []): void
    {
        foreach ($values as $key => $value) {
            $type = $types[$key] ?? self::inferType($value);

            static::updateOrCreate(
                ['group' => $group, 'key' => $key],
                ['value' => self::serializeValue($value, $type), 'type' => $type],
            );
        }

        Cache::forget(self::cacheKey($group));
    }

    private static function castValue(?string $value, string $type): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            'encrypted' => self::tryDecrypt($value),
            default => $value,
        };
    }

    private static function serializeValue(mixed $value, string $type): ?string
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            'encrypted' => Crypt::encryptString((string) $value),
            default => (string) $value,
        };
    }

    private static function tryDecrypt(string $value): ?string
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Throwable) {
            return null;
        }
    }

    private static function inferType(mixed $value): string
    {
        return match (true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_float($value) => 'float',
            is_array($value) => 'json',
            default => 'string',
        };
    }
}
