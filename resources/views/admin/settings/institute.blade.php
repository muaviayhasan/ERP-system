@extends('layouts.admin')

@section('title', 'Institute Profile')

@section('content')
    <x-settings.page
        title="Institute Settings"
        subtitle="Configure your organization's core details and preferences."
        :action="route('settings.institute.update')">

        {{-- Basic Information --}}
        <x-settings.section title="Basic Information" icon="corporate_fare">
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="Institute Full Name" name="full_name" required>
                    <x-settings.input name="full_name" maxlength="255" required
                        value="{{ old('full_name', $s['full_name']) }}" placeholder="Global International"/>
                </x-settings.field>

                <x-settings.field label="Short Name / Code" name="short_name" hint="Shown as a compact badge, e.g. EDU-2024.">
                    <x-settings.input name="short_name" maxlength="50"
                        value="{{ old('short_name', $s['short_name']) }}" placeholder="EDU-2024"/>
                </x-settings.field>

                <x-settings.field label="Institute Type" name="institute_type">
                    <x-settings.select name="institute_type" data-allow-clear placeholder="Select a type...">
                        <option value="">Select a type...</option>
                        @foreach (['University', 'College', 'School', 'Vocational', 'Institute'] as $type)
                            <option value="{{ $type }}" @selected(old('institute_type', $s['institute_type']) === $type)>{{ $type }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>

                <x-settings.field label="Registration / License No." name="registration_number">
                    <x-settings.input name="registration_number" maxlength="255"
                        value="{{ old('registration_number', $s['registration_number']) }}" placeholder="REG-000-111"/>
                </x-settings.field>

                <x-settings.field label="Contact Email" name="contact_email">
                    <x-settings.input type="email" name="contact_email" maxlength="255"
                        value="{{ old('contact_email', $s['contact_email']) }}" placeholder="admin@global-intl.edu"/>
                </x-settings.field>

                <x-settings.field label="Phone Number" name="phone">
                    <x-settings.input name="phone" data-mask="phone" maxlength="12"
                        value="{{ old('phone', $s['phone']) }}" placeholder="0300-0000000"/>
                </x-settings.field>

                <x-settings.field label="Website" name="website">
                    <x-settings.input name="website" maxlength="255"
                        value="{{ old('website', $s['website']) }}" placeholder="www.global-intl.edu"/>
                </x-settings.field>

                <x-settings.field label="Established Year" name="established_year">
                    <x-settings.input type="number" name="established_year" min="1800" max="{{ date('Y') + 1 }}"
                        value="{{ old('established_year', $s['established_year']) }}" placeholder="2010"/>
                </x-settings.field>

                <x-settings.field label="Physical Address" name="address" class="md:col-span-2">
                    <x-settings.textarea name="address" rows="2" maxlength="1000"
                        placeholder="123 Academic Plaza, Education District">{{ old('address', $s['address']) }}</x-settings.textarea>
                </x-settings.field>

                <x-settings.field label="Motto / Tagline" name="motto" class="md:col-span-2">
                    <x-settings.input name="motto" maxlength="255"
                        value="{{ old('motto', $s['motto']) }}" placeholder="Excellence in education, shaping the leaders of tomorrow."/>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- Location --}}
        <x-settings.section title="Location" icon="public" desc="Where your head office is registered.">
            <div class="grid grid-cols-1 gap-md md:grid-cols-3">
                <x-settings.field label="Country" name="country">
                    <x-settings.input name="country" maxlength="120"
                        value="{{ old('country', $s['country']) }}" placeholder="United States"/>
                </x-settings.field>

                <x-settings.field label="State / Province" name="state_province">
                    <x-settings.input name="state_province" maxlength="120"
                        value="{{ old('state_province', $s['state_province']) }}" placeholder="California"/>
                </x-settings.field>

                <x-settings.field label="City" name="city">
                    <x-settings.input name="city" maxlength="120"
                        value="{{ old('city', $s['city']) }}" placeholder="Palo Alto"/>
                </x-settings.field>
            </div>
        </x-settings.section>
    </x-settings.page>
@endsection
