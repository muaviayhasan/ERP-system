@extends('layouts.admin')

@section('title', 'User Default Settings')

@section('content')
    <x-settings.page
        title="User Defaults"
        subtitle="Defaults applied to newly created users and account behaviour."
        :action="route('settings.user-defaults.update')">

        {{-- New User Defaults --}}
        <x-settings.section title="New User Defaults" icon="person_add">
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="Default Role" name="default_role" hint="Assigned to self-registered users.">
                    <x-settings.select name="default_role">
                        <option value="">— None —</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role }}" @selected(old('default_role', $s['default_role']) === $role)>{{ \Illuminate\Support\Str::headline($role) }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Default Status" name="default_status" required>
                    <x-settings.select name="default_status">
                        @foreach (['active' => 'Active', 'pending' => 'Pending', 'inactive' => 'Inactive'] as $val => $lbl)
                            <option value="{{ $val }}" @selected(old('default_status', $s['default_status']) === $val)>{{ $lbl }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
            </div>
            <div class="mt-md grid grid-cols-1 gap-3 sm:grid-cols-3">
                @foreach ([
                    'require_email_verification' => 'Require Email Verification',
                    'send_welcome_email' => 'Send Welcome Email',
                    'auto_generate_password' => 'Auto-generate Password',
                ] as $key => $label)
                    <div class="rounded-lg border border-outline-variant p-4">
                        <x-settings.toggle name="{{ $key }}" label="{{ $label }}"
                            :checked="old($key, $s[$key])"/>
                    </div>
                @endforeach
            </div>
        </x-settings.section>

        {{-- Locale & Display --}}
        <x-settings.section title="Locale & Display" icon="display_settings">
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="Default Language" name="default_language" required>
                    <x-settings.select name="default_language">
                        @foreach ($languages as $code => $name)
                            <option value="{{ $code }}" @selected(old('default_language', $s['default_language']) === $code)>{{ $name }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Default Timezone" name="default_timezone" required>
                    <x-settings.select name="default_timezone">
                        @foreach ($timezones as $tz)
                            <option value="{{ $tz }}" @selected(old('default_timezone', $s['default_timezone']) === $tz)>{{ $tz }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Rows Per Page" name="items_per_page" required
                    hint="Default pagination size for tables.">
                    <x-settings.input type="number" min="5" max="200" name="items_per_page"
                        value="{{ old('items_per_page', $s['items_per_page']) }}"/>
                </x-settings.field>
                <x-settings.field label="Default Theme" name="default_theme" required>
                    <x-settings.select name="default_theme">
                        @foreach (['light' => 'Light', 'dark' => 'Dark', 'system' => 'Follow System'] as $val => $lbl)
                            <option value="{{ $val }}" @selected(old('default_theme', $s['default_theme']) === $val)>{{ $lbl }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
            </div>
        </x-settings.section>
    </x-settings.page>
@endsection
