<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

/**
 * Pushes stored settings into the live framework config at boot, so persisted
 * values actually drive app behaviour (identity, locale, timezone, mail, etc.).
 *
 * Defensive: a no-op until the settings table exists (fresh installs/migrations).
 */
class SettingsApplier
{
    public static function apply(): void
    {
        try {
            if (! Schema::hasTable('settings')) {
                return;
            }
        } catch (\Throwable) {
            return; // no DB connection yet (e.g. during install)
        }

        $general = Setting::group('general');
        $loc = Setting::group('localization');
        $sec = Setting::group('security');
        $notif = Setting::group('notifications');

        // --- Identity ---
        if (! empty($general['institution_name'])) {
            config(['app.name' => $general['institution_name']]);
        }

        // --- Locale & timezone ---
        if (! empty($loc['default_language'])) {
            config(['app.locale' => $loc['default_language']]);
            app()->setLocale($loc['default_language']);
        }
        if (! empty($loc['timezone'])) {
            config(['app.timezone' => $loc['timezone']]);
            date_default_timezone_set($loc['timezone']);
        }

        // --- Sessions & transport security ---
        if (! empty($sec['session_timeout_minutes'])) {
            config(['session.lifetime' => (int) $sec['session_timeout_minutes']]);
        }
        if (! empty($sec['force_https'])) {
            URL::forceScheme('https');
        }

        // --- Mail (from + SMTP transport) ---
        if (! empty($notif['mail_from_address'])) {
            config(['mail.from.address' => $notif['mail_from_address']]);
        }
        if (! empty($notif['mail_from_name'])) {
            config(['mail.from.name' => $notif['mail_from_name']]);
        }
        if (! empty($notif['smtp_host'])) {
            $encryption = $notif['smtp_encryption'] ?? 'tls';
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.host' => $notif['smtp_host'],
                'mail.mailers.smtp.port' => (int) ($notif['smtp_port'] ?? 587),
                'mail.mailers.smtp.username' => $notif['smtp_username'] ?? null,
                'mail.mailers.smtp.password' => $notif['smtp_password'] ?? null, // decrypted by the cast
                'mail.mailers.smtp.encryption' => $encryption === 'none' ? null : $encryption,
            ]);
        }
    }
}
