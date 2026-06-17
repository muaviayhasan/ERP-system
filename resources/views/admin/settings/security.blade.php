@extends('layouts.admin')

@section('title', 'Security Settings')

@section('content')
    <x-settings.page
        title="Security Settings"
        subtitle="Password policy, authentication, sessions, and access control."
        :action="route('settings.security.update')">

        {{-- Password Policy --}}
        <x-settings.section title="Password Policy" icon="password">
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="Minimum Length" name="password_min_length" required>
                    <x-settings.input type="number" min="6" max="64" name="password_min_length"
                        value="{{ old('password_min_length', $s['password_min_length']) }}"/>
                </x-settings.field>
                <x-settings.field label="Password Expiry (days)" name="password_expiry_days" required
                    hint="0 = never expires.">
                    <x-settings.input type="number" min="0" max="365" name="password_expiry_days"
                        value="{{ old('password_expiry_days', $s['password_expiry_days']) }}"/>
                </x-settings.field>
            </div>
            <div class="mt-md grid grid-cols-1 gap-3 sm:grid-cols-3">
                @foreach ([
                    'password_require_uppercase' => 'Require uppercase letter',
                    'password_require_number' => 'Require a number',
                    'password_require_symbol' => 'Require a symbol',
                ] as $key => $label)
                    <div class="rounded-lg border border-outline-variant p-4">
                        <x-settings.toggle name="{{ $key }}" label="{{ $label }}"
                            :checked="old($key, $s[$key])"/>
                    </div>
                @endforeach
            </div>
        </x-settings.section>

        {{-- Authentication --}}
        <x-settings.section title="Authentication & Sessions" icon="lock">
            <div class="mb-md rounded-lg border border-outline-variant p-4">
                <x-settings.toggle name="two_factor_required" label="Require Two-Factor Authentication"
                    desc="Enforce 2FA for all admin users."
                    :checked="old('two_factor_required', $s['two_factor_required'])"/>
            </div>
            <div class="grid grid-cols-1 gap-md md:grid-cols-3">
                <x-settings.field label="Session Timeout (minutes)" name="session_timeout_minutes" required>
                    <x-settings.input type="number" min="5" max="1440" name="session_timeout_minutes"
                        value="{{ old('session_timeout_minutes', $s['session_timeout_minutes']) }}"/>
                </x-settings.field>
                <x-settings.field label="Max Login Attempts" name="max_login_attempts" required>
                    <x-settings.input type="number" min="1" max="20" name="max_login_attempts"
                        value="{{ old('max_login_attempts', $s['max_login_attempts']) }}"/>
                </x-settings.field>
                <x-settings.field label="Lockout Duration (minutes)" name="lockout_minutes" required>
                    <x-settings.input type="number" min="1" max="1440" name="lockout_minutes"
                        value="{{ old('lockout_minutes', $s['lockout_minutes']) }}"/>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- Access Control --}}
        <x-settings.section title="Access Control" icon="admin_panel_settings">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="rounded-lg border border-outline-variant p-4">
                    <x-settings.toggle name="force_https" label="Force HTTPS"
                        desc="Redirect all traffic to a secure connection."
                        :checked="old('force_https', $s['force_https'])"/>
                </div>
                <div class="rounded-lg border border-outline-variant p-4">
                    <x-settings.toggle name="audit_logging" label="Audit Logging"
                        desc="Record admin actions to the activity log."
                        :checked="old('audit_logging', $s['audit_logging'])"/>
                </div>
            </div>
            <div class="mt-md">
                <x-settings.field label="IP Allowlist" name="allowed_ips"
                    hint="One IP or CIDR per line. Leave blank to allow all. Your current IP and loopback are always kept, so you can't lock yourself out.">
                    <x-settings.textarea name="allowed_ips" rows="4" maxlength="2000"
                        placeholder="203.0.113.10&#10;198.51.100.0/24">{{ old('allowed_ips', $s['allowed_ips']) }}</x-settings.textarea>
                </x-settings.field>
            </div>
        </x-settings.section>
    </x-settings.page>
@endsection
