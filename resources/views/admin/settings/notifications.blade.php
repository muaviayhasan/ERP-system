@extends('layouts.admin')

@section('title', 'Notification Settings')

@section('content')
    <x-settings.page
        title="Notification Settings"
        subtitle="Delivery channels, email/SMS providers, and per-event notifications."
        :action="route('settings.notifications.update')">

        {{-- Channels --}}
        <x-settings.section title="Delivery Channels" icon="hub">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'channel_email' => ['Email', 'mail'],
                    'channel_sms' => ['SMS', 'sms'],
                    'channel_push' => ['Push', 'notifications_active'],
                    'channel_inapp' => ['In-App', 'web'],
                ] as $key => [$label, $icon])
                    <div class="rounded-lg border border-outline-variant p-4">
                        <div class="mb-2 flex items-center gap-2 text-on-surface-variant">
                            <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                            <span class="text-label-sm font-bold uppercase tracking-wide">{{ $label }}</span>
                        </div>
                        <x-settings.toggle name="{{ $key }}" :checked="old($key, $s[$key])"/>
                    </div>
                @endforeach
            </div>
        </x-settings.section>

        {{-- Email / SMTP --}}
        <x-settings.section title="Email (SMTP)" icon="mail">
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="From Name" name="mail_from_name">
                    <x-settings.input name="mail_from_name" maxlength="100"
                        value="{{ old('mail_from_name', $s['mail_from_name']) }}"/>
                </x-settings.field>
                <x-settings.field label="From Address" name="mail_from_address">
                    <x-settings.input type="email" name="mail_from_address" maxlength="255"
                        value="{{ old('mail_from_address', $s['mail_from_address']) }}" placeholder="no-reply@example.edu"/>
                </x-settings.field>
                <x-settings.field label="SMTP Host" name="smtp_host">
                    <x-settings.input name="smtp_host" maxlength="255"
                        value="{{ old('smtp_host', $s['smtp_host']) }}" placeholder="smtp.mailgun.org"/>
                </x-settings.field>
                <x-settings.field label="SMTP Port" name="smtp_port">
                    <x-settings.input name="smtp_port" maxlength="10"
                        value="{{ old('smtp_port', $s['smtp_port']) }}" placeholder="587"/>
                </x-settings.field>
                <x-settings.field label="SMTP Username" name="smtp_username">
                    <x-settings.input name="smtp_username" maxlength="255" autocomplete="off"
                        value="{{ old('smtp_username', $s['smtp_username']) }}"/>
                </x-settings.field>
                <x-settings.field label="SMTP Password" name="smtp_password" hint="Stored encrypted.">
                    <x-settings.secret name="smtp_password" :is-set="$secretSet['smtp_password'] ?? false"/>
                </x-settings.field>
                <x-settings.field label="Encryption" name="smtp_encryption" required>
                    <x-settings.select name="smtp_encryption">
                        @foreach (['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'None'] as $val => $lbl)
                            <option value="{{ $val }}" @selected(old('smtp_encryption', $s['smtp_encryption']) === $val)>{{ $lbl }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- SMS Gateway --}}
        <x-settings.section title="SMS Gateway" icon="sms">
            <div class="grid grid-cols-1 gap-md md:grid-cols-3">
                <x-settings.field label="Provider" name="sms_provider">
                    <x-settings.select name="sms_provider">
                        @foreach (['twilio' => 'Twilio', 'vonage' => 'Vonage (Nexmo)', 'textlocal' => 'Textlocal', 'custom' => 'Custom'] as $val => $lbl)
                            <option value="{{ $val }}" @selected(old('sms_provider', $s['sms_provider']) === $val)>{{ $lbl }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="API Key" name="sms_api_key" hint="Stored encrypted.">
                    <x-settings.secret name="sms_api_key" :is-set="$secretSet['sms_api_key'] ?? false"/>
                </x-settings.field>
                <x-settings.field label="Sender ID" name="sms_sender_id">
                    <x-settings.input name="sms_sender_id" maxlength="50"
                        value="{{ old('sms_sender_id', $s['sms_sender_id']) }}"/>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- Events --}}
        <x-settings.section title="Event Notifications" icon="event_note">
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                @foreach ([
                    'event_fee_reminders' => 'Fee Due Reminders',
                    'event_attendance_alerts' => 'Low Attendance Alerts',
                    'event_exam_results' => 'Exam Result Publication',
                    'event_announcements' => 'Announcements & Notices',
                ] as $key => $label)
                    <div class="rounded-lg border border-outline-variant p-4">
                        <x-settings.toggle name="{{ $key }}" label="{{ $label }}"
                            :checked="old($key, $s[$key])"/>
                    </div>
                @endforeach
            </div>
        </x-settings.section>
    </x-settings.page>
@endsection
