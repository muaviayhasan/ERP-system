<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSetting;
use App\Models\Setting;
use App\Support\IpAllowlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * System Settings — one method-pair (show/update) per module page. Key-value
 * settings persist through the cached Setting repository; Academic settings use
 * their own AcademicSetting table. Secrets are stored encrypted and never echoed.
 */
class SettingsController extends Controller implements HasMiddleware
{
    /** Secret keys per group — stored encrypted, masked in the UI, kept if left blank. */
    private const SECRETS = [
        'notifications' => ['smtp_password', 'sms_api_key'],
        'integrations' => ['payment_secret_key', 'recaptcha_secret_key', 'maps_api_key'],
    ];

    public static function middleware(): array
    {
        return [
            new Middleware('can:settings.view', only: [
                'index', 'institute', 'general', 'localization', 'seo', 'academic', 'financial',
                'notifications', 'integrations', 'security', 'userDefaults', 'backup',
            ]),
            new Middleware('can:settings.edit', only: [
                'updateInstitute', 'updateGeneral', 'updateLocalization', 'updateSeo', 'updateAcademic',
                'updateFinancial', 'updateNotifications', 'updateIntegrations',
                'updateSecurity', 'updateUserDefaults', 'createBackup', 'downloadBackup', 'destroyBackup',
            ]),
        ];
    }

    public function index(): RedirectResponse
    {
        return redirect()->route('settings.general');
    }

    /* =============================================================== Institute Profile */

    private function instituteDefaults(): array
    {
        return [
            'full_name' => config('app.name', 'EduCore ERP'),
            'short_name' => '',
            'institute_type' => 'University',
            'registration_number' => '',
            'contact_email' => '',
            'phone' => '',
            'website' => '',
            'address' => '',
            'country' => '',
            'state_province' => '',
            'city' => '',
            'established_year' => '',
            'motto' => '',
        ];
    }

    public function institute(): View
    {
        return view('admin.settings.institute', [
            's' => Setting::groupWithDefaults('institute', $this->instituteDefaults()),
        ]);
    }

    public function updateInstitute(Request $request): RedirectResponse
    {
        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'short_name' => ['nullable', 'string', 'max:50'],
            'institute_type' => ['nullable', 'string', 'max:50'],
            'registration_number' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'country' => ['nullable', 'string', 'max:120'],
            'state_province' => ['nullable', 'string', 'max:120'],
            'city' => ['nullable', 'string', 'max:120'],
            'established_year' => ['nullable', 'integer', 'min:1800', 'max:'.(date('Y') + 1)],
            'motto' => ['nullable', 'string', 'max:255'],
        ]);

        $values = [
            'full_name' => $request->string('full_name')->toString(),
            'short_name' => $request->string('short_name')->toString(),
            'institute_type' => $request->string('institute_type')->toString(),
            'registration_number' => $request->string('registration_number')->toString(),
            'contact_email' => $request->string('contact_email')->toString(),
            'phone' => $request->string('phone')->toString(),
            'website' => $request->string('website')->toString(),
            'address' => $request->string('address')->toString(),
            'country' => $request->string('country')->toString(),
            'state_province' => $request->string('state_province')->toString(),
            'city' => $request->string('city')->toString(),
            'established_year' => $request->input('established_year'),
            'motto' => $request->string('motto')->toString(),
        ];

        Setting::putGroup('institute', $values, [
            'address' => 'text',
            'established_year' => 'integer',
        ]);

        return back()->with('status', 'Institute profile saved.');
    }

    /* ===================================================================== General */

    private function generalDefaults(): array
    {
        return [
            'institution_name' => config('app.name', 'EduCore ERP'),
            'tagline' => '',
            'contact_email' => '',
            'phone' => '',
            'address' => '',
            'light_logo' => null,
            'dark_logo' => null,
            'favicon' => null,
            'maintenance_mode' => false,
            'debug_mode' => false,
        ];
    }

    public function general(): View
    {
        return view('admin.settings.general', [
            's' => Setting::groupWithDefaults('general', $this->generalDefaults()),
        ]);
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $request->validate([
            'institution_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'light_logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:5120'],
            'dark_logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:5120'],
            'favicon' => ['nullable', 'image', 'mimes:png,ico,svg', 'max:1024'],
        ]);

        $values = [
            'institution_name' => $request->string('institution_name')->toString(),
            'tagline' => $request->string('tagline')->toString(),
            'contact_email' => $request->string('contact_email')->toString(),
            'phone' => $request->string('phone')->toString(),
            'address' => $request->string('address')->toString(),
            'maintenance_mode' => $request->boolean('maintenance_mode'),
            'debug_mode' => $request->boolean('debug_mode'),
        ];
        $types = [
            'address' => 'text',
            'maintenance_mode' => 'boolean',
            'debug_mode' => 'boolean',
        ];

        foreach (['light_logo', 'dark_logo', 'favicon'] as $field) {
            if ($request->hasFile($field)) {
                $values[$field] = $request->file($field)->store('settings', 'public');
                $types[$field] = 'string';
            }
        }

        Setting::putGroup('general', $values, $types);

        return back()->with('status', 'General settings saved.');
    }

    /* ================================================================ Localization */

    private function localizationDefaults(): array
    {
        return [
            'default_language' => 'en',
            'multi_language' => true,
            'supported_languages' => ['en'],
            'rtl' => false,
            'auto_detect_language' => true,
            'region' => 'PK',
            'currency' => 'PKR',
            'number_format' => 'us',
            'timezone' => 'Asia/Karachi',
            'date_format' => 'd/m/Y',
            'time_format' => '12',
            'week_start' => 'monday',
            'auto_convert_timezone' => true,
        ];
    }

    public function localization(): View
    {
        return view('admin.settings.localization', [
            's' => Setting::groupWithDefaults('localization', $this->localizationDefaults()),
            'languages' => $this->languageOptions(),
            'currencies' => $this->currencyOptions(),
            'timezones' => $this->timezoneOptions(),
        ]);
    }

    public function updateLocalization(Request $request): RedirectResponse
    {
        $request->validate([
            'default_language' => ['required', 'string', 'max:10'],
            'supported_languages' => ['nullable', 'array'],
            'supported_languages.*' => ['string', 'max:10'],
            'region' => ['nullable', 'string', 'max:10'],
            'currency' => ['nullable', 'string', 'max:10'],
            'number_format' => ['required', 'in:us,eu'],
            'timezone' => ['required', 'timezone'],
            'date_format' => ['required', 'string', 'max:20'],
            'time_format' => ['required', 'in:12,24'],
            'week_start' => ['required', 'in:monday,sunday,saturday'],
        ]);

        Setting::putGroup('localization', [
            'default_language' => $request->string('default_language')->toString(),
            'supported_languages' => $request->input('supported_languages', []),
            'multi_language' => $request->boolean('multi_language'),
            'rtl' => $request->boolean('rtl'),
            'auto_detect_language' => $request->boolean('auto_detect_language'),
            'region' => $request->string('region')->toString(),
            'currency' => $request->string('currency')->toString(),
            'number_format' => $request->string('number_format')->toString(),
            'timezone' => $request->string('timezone')->toString(),
            'date_format' => $request->string('date_format')->toString(),
            'time_format' => $request->string('time_format')->toString(),
            'week_start' => $request->string('week_start')->toString(),
            'auto_convert_timezone' => $request->boolean('auto_convert_timezone'),
        ], [
            'supported_languages' => 'json',
            'multi_language' => 'boolean',
            'rtl' => 'boolean',
            'auto_detect_language' => 'boolean',
            'auto_convert_timezone' => 'boolean',
        ]);

        return back()->with('status', 'Localization settings saved.');
    }

    /* ========================================================================= SEO */

    private function seoDefaults(): array
    {
        return [
            'meta_title' => config('app.name', 'EduCore ERP'),
            'meta_description' => '',
            'meta_keywords' => '',
            'author_name' => '',
            'canonical_base' => '',
            'auto_meta' => true,
            'open_graph' => true,
            'search_indexing' => true,
            'follow_links' => true,
            'robots_meta' => 'index, follow',
            'robots_txt' => "User-agent: *\nDisallow: /admin/\nDisallow: /api/\n",
            'seo_friendly_urls' => true,
            'slug_format' => 'lowercase-hyphenated',
            'lazy_loading' => true,
            'image_alt_autofill' => false,
            'minify_assets' => true,
            'gzip' => true,
        ];
    }

    public function seo(): View
    {
        return view('admin.settings.seo', [
            's' => Setting::groupWithDefaults('seo', $this->seoDefaults()),
        ]);
    }

    public function updateSeo(Request $request): RedirectResponse
    {
        $request->validate([
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'canonical_base' => ['nullable', 'url', 'max:255'],
            'robots_meta' => ['required', 'in:index, follow,noindex, follow,index, nofollow,noindex, nofollow'],
            'robots_txt' => ['nullable', 'string', 'max:5000'],
            'slug_format' => ['required', 'in:lowercase-hyphenated,CamelCase,underscore_separation'],
        ]);

        Setting::putGroup('seo', [
            'meta_title' => $request->string('meta_title')->toString(),
            'meta_description' => $request->string('meta_description')->toString(),
            'meta_keywords' => $request->string('meta_keywords')->toString(),
            'author_name' => $request->string('author_name')->toString(),
            'canonical_base' => $request->string('canonical_base')->toString(),
            'auto_meta' => $request->boolean('auto_meta'),
            'open_graph' => $request->boolean('open_graph'),
            'search_indexing' => $request->boolean('search_indexing'),
            'follow_links' => $request->boolean('follow_links'),
            'robots_meta' => $request->string('robots_meta')->toString(),
            'robots_txt' => $request->string('robots_txt')->toString(),
            'seo_friendly_urls' => $request->boolean('seo_friendly_urls'),
            'slug_format' => $request->string('slug_format')->toString(),
            'lazy_loading' => $request->boolean('lazy_loading'),
            'image_alt_autofill' => $request->boolean('image_alt_autofill'),
            'minify_assets' => $request->boolean('minify_assets'),
            'gzip' => $request->boolean('gzip'),
        ], [
            'meta_description' => 'text',
            'robots_txt' => 'text',
            'auto_meta' => 'boolean',
            'open_graph' => 'boolean',
            'search_indexing' => 'boolean',
            'follow_links' => 'boolean',
            'seo_friendly_urls' => 'boolean',
            'lazy_loading' => 'boolean',
            'image_alt_autofill' => 'boolean',
            'minify_assets' => 'boolean',
            'gzip' => 'boolean',
        ]);

        return back()->with('status', 'SEO settings saved.');
    }

    /* ==================================================================== Academic */

    public function academic(): View
    {
        return view('admin.settings.academic', [
            'a' => AcademicSetting::query()->firstOrNew(['academic_year_id' => null]),
        ]);
    }

    public function updateAcademic(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'grading_system' => ['required', 'string', 'max:100'],
            'pass_mark_threshold' => ['required', 'integer', 'between:0,100'],
            'min_attendance_required' => ['required', 'integer', 'between:0,100'],
            'attendance_grace_minutes' => ['required', 'integer', 'min:0', 'max:240'],
            'attendance_session_limit' => ['nullable', 'string', 'max:50'],
            'attendance_warning_threshold' => ['required', 'integer', 'between:0,100'],
            'attendance_critical_threshold' => ['required', 'integer', 'between:0,100'],
            'exam_structure' => ['required', 'string', 'max:100'],
            'weight_final' => ['required', 'integer', 'between:0,100'],
            'weight_midterm' => ['required', 'integer', 'between:0,100'],
            'weight_assignments_lab' => ['required', 'integer', 'between:0,100'],
            'weight_quizzes' => ['required', 'integer', 'between:0,100'],
            'promotion_min_gpa' => ['nullable', 'numeric', 'between:0,10'],
            'promotion_max_fail_subjects' => ['nullable', 'integer', 'min:0', 'max:20'],
            'min_credit_load' => ['nullable', 'integer', 'min:0', 'max:60'],
            'max_credit_load' => ['nullable', 'integer', 'min:0', 'max:60'],
            'year_start_month' => ['nullable', 'string', 'max:20'],
            'makeup_class_allowance' => ['nullable', 'string', 'max:50'],
        ]);

        $academic = AcademicSetting::query()->firstOrNew(['academic_year_id' => null]);
        $academic->fill($data);
        $academic->promotion_enabled = $request->boolean('promotion_enabled');
        $academic->university_mode_enabled = $request->boolean('university_mode_enabled');
        $academic->save();

        return back()->with('status', 'Academic settings saved.');
    }

    /* =================================================================== Financial */

    private function financialDefaults(): array
    {
        return [
            'base_currency' => 'PKR',
            'currency_symbol' => '₨',
            'currency_position' => 'before',
            'decimal_places' => 2,
            'thousand_separator' => ',',
            'decimal_separator' => '.',
            'fiscal_year_start' => 'July',
            'invoice_prefix' => 'INV-',
            'receipt_prefix' => 'RCT-',
            'tax_enabled' => false,
            'tax_name' => 'GST',
            'tax_rate' => 0.0,
            'late_fee_enabled' => false,
            'late_fee_type' => 'fixed',
            'late_fee_amount' => 0.0,
            'payment_methods' => ['cash'],
            'payment_terms_days' => 30,
            'rounding' => 'none',
        ];
    }

    public function financial(): View
    {
        return view('admin.settings.financial', [
            's' => Setting::groupWithDefaults('finance', $this->financialDefaults()),
            'currencies' => $this->currencyOptions(),
            'months' => $this->monthOptions(),
        ]);
    }

    public function updateFinancial(Request $request): RedirectResponse
    {
        $request->validate([
            'base_currency' => ['required', 'string', 'max:10'],
            'currency_symbol' => ['required', 'string', 'max:8'],
            'currency_position' => ['required', 'in:before,after'],
            'decimal_places' => ['required', 'integer', 'between:0,4'],
            'thousand_separator' => ['nullable', 'string', 'max:2'],
            'decimal_separator' => ['required', 'string', 'max:2'],
            'fiscal_year_start' => ['required', 'string', 'max:20'],
            'invoice_prefix' => ['nullable', 'string', 'max:20'],
            'receipt_prefix' => ['nullable', 'string', 'max:20'],
            'tax_name' => ['nullable', 'string', 'max:50'],
            'tax_rate' => ['required', 'numeric', 'between:0,100'],
            'late_fee_type' => ['required', 'in:fixed,percent'],
            'late_fee_amount' => ['required', 'numeric', 'min:0'],
            'payment_methods' => ['nullable', 'array'],
            'payment_methods.*' => ['string', 'max:30'],
            'payment_terms_days' => ['required', 'integer', 'min:0', 'max:365'],
            'rounding' => ['required', 'in:none,nearest,up,down'],
        ]);

        Setting::putGroup('finance', [
            'base_currency' => $request->string('base_currency')->toString(),
            'currency_symbol' => $request->string('currency_symbol')->toString(),
            'currency_position' => $request->string('currency_position')->toString(),
            'decimal_places' => $request->integer('decimal_places'),
            'thousand_separator' => $request->string('thousand_separator')->toString(),
            'decimal_separator' => $request->string('decimal_separator')->toString(),
            'fiscal_year_start' => $request->string('fiscal_year_start')->toString(),
            'invoice_prefix' => $request->string('invoice_prefix')->toString(),
            'receipt_prefix' => $request->string('receipt_prefix')->toString(),
            'tax_enabled' => $request->boolean('tax_enabled'),
            'tax_name' => $request->string('tax_name')->toString(),
            'tax_rate' => (float) $request->input('tax_rate'),
            'late_fee_enabled' => $request->boolean('late_fee_enabled'),
            'late_fee_type' => $request->string('late_fee_type')->toString(),
            'late_fee_amount' => (float) $request->input('late_fee_amount'),
            'payment_methods' => $request->input('payment_methods', []),
            'payment_terms_days' => $request->integer('payment_terms_days'),
            'rounding' => $request->string('rounding')->toString(),
        ], [
            'decimal_places' => 'integer',
            'tax_enabled' => 'boolean',
            'tax_rate' => 'float',
            'late_fee_enabled' => 'boolean',
            'late_fee_amount' => 'float',
            'payment_methods' => 'json',
            'payment_terms_days' => 'integer',
        ]);

        return back()->with('status', 'Financial settings saved.');
    }

    /* =============================================================== Notifications */

    private function notificationsDefaults(): array
    {
        return [
            'channel_email' => true,
            'channel_sms' => false,
            'channel_push' => false,
            'channel_inapp' => true,
            'mail_from_name' => config('app.name', 'EduCore ERP'),
            'mail_from_address' => '',
            'smtp_host' => '',
            'smtp_port' => '587',
            'smtp_username' => '',
            'smtp_encryption' => 'tls',
            'sms_provider' => 'twilio',
            'sms_sender_id' => '',
            'event_fee_reminders' => true,
            'event_attendance_alerts' => true,
            'event_exam_results' => true,
            'event_announcements' => true,
        ];
    }

    public function notifications(): View
    {
        return view('admin.settings.notifications', $this->withSecretFlags('notifications', [
            's' => Setting::groupWithDefaults('notifications', $this->notificationsDefaults()),
        ]));
    }

    public function updateNotifications(Request $request): RedirectResponse
    {
        $request->validate([
            'mail_from_name' => ['nullable', 'string', 'max:100'],
            'mail_from_address' => ['nullable', 'email', 'max:255'],
            'smtp_host' => ['nullable', 'string', 'max:255'],
            'smtp_port' => ['nullable', 'string', 'max:10'],
            'smtp_username' => ['nullable', 'string', 'max:255'],
            'smtp_password' => ['nullable', 'string', 'max:255'],
            'smtp_encryption' => ['required', 'in:tls,ssl,none'],
            'sms_provider' => ['nullable', 'string', 'max:50'],
            'sms_api_key' => ['nullable', 'string', 'max:255'],
            'sms_sender_id' => ['nullable', 'string', 'max:50'],
        ]);

        $values = [
            'channel_email' => $request->boolean('channel_email'),
            'channel_sms' => $request->boolean('channel_sms'),
            'channel_push' => $request->boolean('channel_push'),
            'channel_inapp' => $request->boolean('channel_inapp'),
            'mail_from_name' => $request->string('mail_from_name')->toString(),
            'mail_from_address' => $request->string('mail_from_address')->toString(),
            'smtp_host' => $request->string('smtp_host')->toString(),
            'smtp_port' => $request->string('smtp_port')->toString(),
            'smtp_username' => $request->string('smtp_username')->toString(),
            'smtp_encryption' => $request->string('smtp_encryption')->toString(),
            'sms_provider' => $request->string('sms_provider')->toString(),
            'sms_sender_id' => $request->string('sms_sender_id')->toString(),
            'event_fee_reminders' => $request->boolean('event_fee_reminders'),
            'event_attendance_alerts' => $request->boolean('event_attendance_alerts'),
            'event_exam_results' => $request->boolean('event_exam_results'),
            'event_announcements' => $request->boolean('event_announcements'),
        ];
        $types = [
            'channel_email' => 'boolean', 'channel_sms' => 'boolean',
            'channel_push' => 'boolean', 'channel_inapp' => 'boolean',
            'event_fee_reminders' => 'boolean', 'event_attendance_alerts' => 'boolean',
            'event_exam_results' => 'boolean', 'event_announcements' => 'boolean',
        ];

        [$values, $types] = $this->mergeSecrets($request, 'notifications', $values, $types);

        Setting::putGroup('notifications', $values, $types);

        return back()->with('status', 'Notification settings saved.');
    }

    /* ================================================================ Integrations */

    private function integrationsDefaults(): array
    {
        return [
            'payment_enabled' => false,
            'payment_provider' => 'stripe',
            'payment_public_key' => '',
            'analytics_enabled' => false,
            'analytics_measurement_id' => '',
            'recaptcha_enabled' => false,
            'recaptcha_site_key' => '',
            'maps_enabled' => false,
        ];
    }

    public function integrations(): View
    {
        return view('admin.settings.integrations', $this->withSecretFlags('integrations', [
            's' => Setting::groupWithDefaults('integrations', $this->integrationsDefaults()),
        ]));
    }

    public function updateIntegrations(Request $request): RedirectResponse
    {
        $request->validate([
            'payment_provider' => ['nullable', 'string', 'max:50'],
            'payment_public_key' => ['nullable', 'string', 'max:255'],
            'payment_secret_key' => ['nullable', 'string', 'max:255'],
            'analytics_measurement_id' => ['nullable', 'string', 'max:100'],
            'recaptcha_site_key' => ['nullable', 'string', 'max:255'],
            'recaptcha_secret_key' => ['nullable', 'string', 'max:255'],
            'maps_api_key' => ['nullable', 'string', 'max:255'],
        ]);

        $values = [
            'payment_enabled' => $request->boolean('payment_enabled'),
            'payment_provider' => $request->string('payment_provider')->toString(),
            'payment_public_key' => $request->string('payment_public_key')->toString(),
            'analytics_enabled' => $request->boolean('analytics_enabled'),
            'analytics_measurement_id' => $request->string('analytics_measurement_id')->toString(),
            'recaptcha_enabled' => $request->boolean('recaptcha_enabled'),
            'recaptcha_site_key' => $request->string('recaptcha_site_key')->toString(),
            'maps_enabled' => $request->boolean('maps_enabled'),
        ];
        $types = [
            'payment_enabled' => 'boolean',
            'analytics_enabled' => 'boolean',
            'recaptcha_enabled' => 'boolean',
            'maps_enabled' => 'boolean',
        ];

        [$values, $types] = $this->mergeSecrets($request, 'integrations', $values, $types);

        Setting::putGroup('integrations', $values, $types);

        return back()->with('status', 'Integration settings saved.');
    }

    /* ==================================================================== Security */

    private function securityDefaults(): array
    {
        return [
            'password_min_length' => 8,
            'password_require_uppercase' => false,
            'password_require_number' => false,
            'password_require_symbol' => false,
            'password_expiry_days' => 0,
            'two_factor_required' => false,
            'session_timeout_minutes' => 120,
            'max_login_attempts' => 5,
            'lockout_minutes' => 15,
            'force_https' => false,
            'audit_logging' => true,
            'allowed_ips' => '',
        ];
    }

    public function security(): View
    {
        return view('admin.settings.security', [
            's' => Setting::groupWithDefaults('security', $this->securityDefaults()),
        ]);
    }

    public function updateSecurity(Request $request): RedirectResponse
    {
        $request->validate([
            'password_min_length' => ['required', 'integer', 'between:6,64'],
            'password_expiry_days' => ['required', 'integer', 'between:0,365'],
            'session_timeout_minutes' => ['required', 'integer', 'between:5,1440'],
            'max_login_attempts' => ['required', 'integer', 'between:1,20'],
            'lockout_minutes' => ['required', 'integer', 'between:1,1440'],
            'allowed_ips' => ['nullable', 'string', 'max:2000'],
        ]);

        // Self-bypass: ensure the configuring admin's own IP stays in the allowlist
        // so a typo can never lock everyone out. Also normalizes the stored text.
        $ipEntries = IpAllowlist::parse($request->input('allowed_ips'));
        if ($ipEntries !== [] && ! IpAllowlist::allows($request->ip(), $ipEntries)) {
            $ipEntries[] = $request->ip();
        }

        Setting::putGroup('security', [
            'password_min_length' => $request->integer('password_min_length'),
            'password_require_uppercase' => $request->boolean('password_require_uppercase'),
            'password_require_number' => $request->boolean('password_require_number'),
            'password_require_symbol' => $request->boolean('password_require_symbol'),
            'password_expiry_days' => $request->integer('password_expiry_days'),
            'two_factor_required' => $request->boolean('two_factor_required'),
            'session_timeout_minutes' => $request->integer('session_timeout_minutes'),
            'max_login_attempts' => $request->integer('max_login_attempts'),
            'lockout_minutes' => $request->integer('lockout_minutes'),
            'force_https' => $request->boolean('force_https'),
            'audit_logging' => $request->boolean('audit_logging'),
            'allowed_ips' => implode("\n", $ipEntries),
        ], [
            'password_min_length' => 'integer',
            'password_require_uppercase' => 'boolean',
            'password_require_number' => 'boolean',
            'password_require_symbol' => 'boolean',
            'password_expiry_days' => 'integer',
            'two_factor_required' => 'boolean',
            'session_timeout_minutes' => 'integer',
            'max_login_attempts' => 'integer',
            'lockout_minutes' => 'integer',
            'force_https' => 'boolean',
            'audit_logging' => 'boolean',
            'allowed_ips' => 'text',
        ]);

        return back()->with('status', 'Security settings saved.');
    }

    /* =============================================================== User Defaults */

    private function userDefaultsDefaults(): array
    {
        return [
            'default_role' => '',
            'default_status' => 'active',
            'default_language' => 'en',
            'default_timezone' => 'Asia/Karachi',
            'items_per_page' => 15,
            'default_theme' => 'light',
            'require_email_verification' => false,
            'send_welcome_email' => true,
            'auto_generate_password' => false,
        ];
    }

    public function userDefaults(): View
    {
        return view('admin.settings.user-defaults', [
            's' => Setting::groupWithDefaults('user_defaults', $this->userDefaultsDefaults()),
            'roles' => Role::orderBy('name')->pluck('name'),
            'languages' => $this->languageOptions(),
            'timezones' => $this->timezoneOptions(),
        ]);
    }

    public function updateUserDefaults(Request $request): RedirectResponse
    {
        $request->validate([
            'default_role' => ['nullable', 'string', 'exists:roles,name'],
            'default_status' => ['required', 'in:active,inactive,pending'],
            'default_language' => ['required', 'string', 'max:10'],
            'default_timezone' => ['required', 'timezone'],
            'items_per_page' => ['required', 'integer', 'between:5,200'],
            'default_theme' => ['required', 'in:light,dark,system'],
        ]);

        Setting::putGroup('user_defaults', [
            'default_role' => $request->string('default_role')->toString(),
            'default_status' => $request->string('default_status')->toString(),
            'default_language' => $request->string('default_language')->toString(),
            'default_timezone' => $request->string('default_timezone')->toString(),
            'items_per_page' => $request->integer('items_per_page'),
            'default_theme' => $request->string('default_theme')->toString(),
            'require_email_verification' => $request->boolean('require_email_verification'),
            'send_welcome_email' => $request->boolean('send_welcome_email'),
            'auto_generate_password' => $request->boolean('auto_generate_password'),
        ], [
            'items_per_page' => 'integer',
            'require_email_verification' => 'boolean',
            'send_welcome_email' => 'boolean',
            'auto_generate_password' => 'boolean',
        ]);

        return back()->with('status', 'User default settings saved.');
    }

    /* ====================================================================== Backup */

    public function backup(): View
    {
        return view('admin.settings.backup', [
            'backups' => $this->listBackups(),
            'driver' => config('database.default'),
        ]);
    }

    public function createBackup(): RedirectResponse
    {
        $dir = storage_path('app/backups');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $connection = config('database.default');
        $config = config("database.connections.{$connection}");
        $driver = $config['driver'] ?? null;
        $stamp = now()->format('Y_m_d_His');

        if ($driver === 'sqlite') {
            $source = $config['database'];
            if (! is_file($source)) {
                return back()->withErrors(['backup' => 'SQLite database file was not found.']);
            }
            copy($source, "{$dir}/backup_{$stamp}.sqlite");

            return back()->with('status', 'Database backup created successfully.');
        }

        if ($driver === 'mysql') {
            $target = "{$dir}/backup_{$stamp}.sql";
            $result = Process::timeout(600)
                ->env(['MYSQL_PWD' => (string) ($config['password'] ?? '')])
                ->run([
                    'mysqldump',
                    '--host='.($config['host'] ?? '127.0.0.1'),
                    '--port='.($config['port'] ?? '3306'),
                    '--user='.($config['username'] ?? 'root'),
                    '--single-transaction',
                    '--skip-lock-tables',
                    $config['database'],
                ]);

            if ($result->failed()) {
                return back()->withErrors([
                    'backup' => 'mysqldump failed (is it installed and on PATH?): '.Str::limit($result->errorOutput(), 300),
                ]);
            }
            file_put_contents($target, $result->output());

            return back()->with('status', 'Database backup created successfully.');
        }

        return back()->withErrors(['backup' => "Backups are not supported for the '{$driver}' driver yet."]);
    }

    public function downloadBackup(string $file): BinaryFileResponse
    {
        $path = storage_path('app/backups/'.basename($file));
        abort_unless(is_file($path), 404);

        return response()->download($path);
    }

    public function destroyBackup(string $file): RedirectResponse
    {
        $path = storage_path('app/backups/'.basename($file));
        if (is_file($path)) {
            @unlink($path);
        }

        return back()->with('status', 'Backup deleted.');
    }

    private function listBackups(): array
    {
        $dir = storage_path('app/backups');
        if (! is_dir($dir)) {
            return [];
        }

        return collect(glob($dir.'/*'))
            ->filter(fn ($p) => is_file($p))
            ->map(fn ($p) => [
                'name' => basename($p),
                'size' => $this->humanSize(filesize($p)),
                'date' => date('M j, Y g:i A', filemtime($p)),
                'timestamp' => filemtime($p),
            ])
            ->sortByDesc('timestamp')
            ->values()
            ->all();
    }

    /* ====================================================================== Shared */

    /** Merge masked secrets into the value/type arrays only when provided. */
    private function mergeSecrets(Request $request, string $group, array $values, array $types): array
    {
        foreach (self::SECRETS[$group] ?? [] as $key) {
            if ($request->filled($key)) {
                $values[$key] = $request->string($key)->toString();
                $types[$key] = 'encrypted';
            }
        }

        return [$values, $types];
    }

    /** Strip secret values from view data, exposing only a "<key>_set" boolean. */
    private function withSecretFlags(string $group, array $data): array
    {
        foreach (self::SECRETS[$group] ?? [] as $key) {
            $data['secretSet'][$key] = ! empty($data['s'][$key]);
            unset($data['s'][$key]);
        }

        return $data;
    }

    private function languageOptions(): array
    {
        return [
            'en' => 'English', 'ur' => 'Urdu', 'ar' => 'Arabic', 'es' => 'Spanish',
            'fr' => 'French', 'de' => 'German', 'zh' => 'Chinese', 'hi' => 'Hindi',
        ];
    }

    private function currencyOptions(): array
    {
        return [
            'PKR' => 'PKR — Pakistani Rupee (₨)',
            'USD' => 'USD — US Dollar ($)',
            'EUR' => 'EUR — Euro (€)',
            'GBP' => 'GBP — Pound Sterling (£)',
            'AED' => 'AED — UAE Dirham (د.إ)',
            'SAR' => 'SAR — Saudi Riyal (﷼)',
            'INR' => 'INR — Indian Rupee (₹)',
        ];
    }

    private function monthOptions(): array
    {
        return [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
        ];
    }

    private function timezoneOptions(): array
    {
        return timezone_identifiers_list();
    }
}
