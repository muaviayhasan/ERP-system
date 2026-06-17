@extends('layouts.admin')

@section('title', 'Integration Settings')

@section('content')
    <x-settings.page
        title="Integrations"
        subtitle="Third-party services and API credentials. Secret keys are stored encrypted."
        :action="route('settings.integrations.update')">

        {{-- Payment Gateway --}}
        <x-settings.section title="Payment Gateway" icon="credit_card">
            <x-slot:header>
                <x-settings.toggle name="payment_enabled" :checked="old('payment_enabled', $s['payment_enabled'])"/>
            </x-slot:header>
            <div class="grid grid-cols-1 gap-md md:grid-cols-3">
                <x-settings.field label="Provider" name="payment_provider">
                    <x-settings.select name="payment_provider">
                        @foreach (['stripe' => 'Stripe', 'paypal' => 'PayPal', 'razorpay' => 'Razorpay', 'custom' => 'Custom'] as $val => $lbl)
                            <option value="{{ $val }}" @selected(old('payment_provider', $s['payment_provider']) === $val)>{{ $lbl }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Public / Publishable Key" name="payment_public_key">
                    <x-settings.input name="payment_public_key" maxlength="255" autocomplete="off"
                        value="{{ old('payment_public_key', $s['payment_public_key']) }}"/>
                </x-settings.field>
                <x-settings.field label="Secret Key" name="payment_secret_key" hint="Stored encrypted.">
                    <x-settings.secret name="payment_secret_key" :is-set="$secretSet['payment_secret_key'] ?? false"/>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- Google Analytics --}}
        <x-settings.section title="Google Analytics" icon="analytics">
            <x-slot:header>
                <x-settings.toggle name="analytics_enabled" :checked="old('analytics_enabled', $s['analytics_enabled'])"/>
            </x-slot:header>
            <x-settings.field label="Measurement ID" name="analytics_measurement_id" hint="e.g. G-XXXXXXXXXX">
                <x-settings.input name="analytics_measurement_id" maxlength="100"
                    value="{{ old('analytics_measurement_id', $s['analytics_measurement_id']) }}"/>
            </x-settings.field>
        </x-settings.section>

        {{-- reCAPTCHA --}}
        <x-settings.section title="Google reCAPTCHA" icon="verified_user">
            <x-slot:header>
                <x-settings.toggle name="recaptcha_enabled" :checked="old('recaptcha_enabled', $s['recaptcha_enabled'])"/>
            </x-slot:header>
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="Site Key" name="recaptcha_site_key">
                    <x-settings.input name="recaptcha_site_key" maxlength="255" autocomplete="off"
                        value="{{ old('recaptcha_site_key', $s['recaptcha_site_key']) }}"/>
                </x-settings.field>
                <x-settings.field label="Secret Key" name="recaptcha_secret_key" hint="Stored encrypted.">
                    <x-settings.secret name="recaptcha_secret_key" :is-set="$secretSet['recaptcha_secret_key'] ?? false"/>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- Google Maps --}}
        <x-settings.section title="Google Maps" icon="map">
            <x-slot:header>
                <x-settings.toggle name="maps_enabled" :checked="old('maps_enabled', $s['maps_enabled'])"/>
            </x-slot:header>
            <x-settings.field label="API Key" name="maps_api_key" hint="Stored encrypted.">
                <x-settings.secret name="maps_api_key" :is-set="$secretSet['maps_api_key'] ?? false"/>
            </x-settings.field>
        </x-settings.section>
    </x-settings.page>
@endsection
