@extends('layouts.admin')

@section('title', 'Localization Settings')

@php
    $supported = old('supported_languages', $s['supported_languages'] ?? []);
    $regions = ['PK' => 'Pakistan', 'GB' => 'United Kingdom', 'AE' => 'United Arab Emirates', 'US' => 'United States', 'SA' => 'Saudi Arabia', 'IN' => 'India'];
    $dateFormats = ['d/m/Y' => '31/12/2025  (DD/MM/YYYY)', 'm/d/Y' => '12/31/2025  (MM/DD/YYYY)', 'Y-m-d' => '2025-12-31  (ISO 8601)', 'F j, Y' => 'December 31, 2025'];
@endphp

@section('content')
    <x-settings.page
        title="Localization Settings"
        subtitle="Languages, regional formats, currency, and date/time handling."
        :action="route('settings.localization.update')">

        {{-- Language Configuration --}}
        <x-settings.section title="Language Configuration" icon="translate">
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="Default Language" name="default_language" required>
                    <x-settings.select name="default_language">
                        @foreach ($languages as $code => $name)
                            <option value="{{ $code }}" @selected(old('default_language', $s['default_language']) === $code)>{{ $name }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>

                <x-settings.field label="Supported Languages" name="supported_languages"
                    hint="Languages users can switch between.">
                    <x-settings.select name="supported_languages[]" multiple data-select2-parent placeholder="Select languages...">
                        @foreach ($languages as $code => $name)
                            <option value="{{ $code }}" @selected(in_array($code, $supported))>{{ $name }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
            </div>

            <div class="mt-md grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-lg border border-outline-variant p-4">
                    <x-settings.toggle name="multi_language" label="Multi-language Support"
                        :checked="old('multi_language', $s['multi_language'])"/>
                </div>
                <div class="rounded-lg border border-outline-variant p-4">
                    <x-settings.toggle name="rtl" label="Global RTL Layout"
                        :checked="old('rtl', $s['rtl'])"/>
                </div>
                <div class="rounded-lg border border-outline-variant p-4">
                    <x-settings.toggle name="auto_detect_language" label="Auto-detect Language"
                        :checked="old('auto_detect_language', $s['auto_detect_language'])"/>
                </div>
            </div>
        </x-settings.section>

        {{-- Regional Defaults --}}
        <x-settings.section title="Regional Defaults" icon="public">
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="Primary Region" name="region">
                    <x-settings.select name="region">
                        @foreach ($regions as $code => $name)
                            <option value="{{ $code }}" @selected(old('region', $s['region']) === $code)>{{ $name }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>

                <x-settings.field label="Currency" name="currency" hint="Default currency for new transactions.">
                    <x-settings.select name="currency">
                        @foreach ($currencies as $code => $name)
                            <option value="{{ $code }}" @selected(old('currency', $s['currency']) === $code)>{{ $name }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>

                <x-settings.field label="Number Format" name="number_format" class="md:col-span-2">
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        @foreach (['us' => 'US Style — 1,000,000.00', 'eu' => 'EU Style — 1.000.000,00'] as $val => $lbl)
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-outline-variant p-3 hover:bg-surface-container-low">
                                <input type="radio" name="number_format" value="{{ $val }}"
                                       @checked(old('number_format', $s['number_format']) === $val)
                                       class="text-primary focus:ring-primary">
                                <span class="text-body-md text-on-surface">{{ $lbl }}</span>
                            </label>
                        @endforeach
                    </div>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- Date & Time --}}
        <x-settings.section title="Date & Time" icon="schedule">
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="System Timezone" name="timezone" required>
                    <x-settings.select name="timezone">
                        @foreach ($timezones as $tz)
                            <option value="{{ $tz }}" @selected(old('timezone', $s['timezone']) === $tz)>{{ $tz }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>

                <x-settings.field label="Date Format" name="date_format" required>
                    <x-settings.select name="date_format">
                        @foreach ($dateFormats as $val => $lbl)
                            <option value="{{ $val }}" @selected(old('date_format', $s['date_format']) === $val)>{{ $lbl }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>

                <x-settings.field label="Time Format" name="time_format" required>
                    <x-settings.select name="time_format">
                        <option value="12" @selected(old('time_format', $s['time_format']) === '12')>12-hour (02:45 PM)</option>
                        <option value="24" @selected(old('time_format', $s['time_format']) === '24')>24-hour (14:45)</option>
                    </x-settings.select>
                </x-settings.field>

                <x-settings.field label="Week Starts On" name="week_start" required>
                    <x-settings.select name="week_start">
                        @foreach (['monday' => 'Monday', 'sunday' => 'Sunday', 'saturday' => 'Saturday'] as $val => $lbl)
                            <option value="{{ $val }}" @selected(old('week_start', $s['week_start']) === $val)>{{ $lbl }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
            </div>

            <div class="mt-md rounded-lg border border-outline-variant p-4">
                <x-settings.toggle name="auto_convert_timezone" label="Auto-convert to User Timezone"
                    desc="Display dates and times in each user's local timezone."
                    :checked="old('auto_convert_timezone', $s['auto_convert_timezone'])"/>
            </div>
        </x-settings.section>
    </x-settings.page>
@endsection
