@extends('layouts.admin')

@section('title', 'General Settings')

@section('content')
    <x-settings.page
        title="General Settings"
        subtitle="Institution identity, branding, and core system parameters."
        :action="route('settings.general.update')">

        {{-- Institution Identity --}}
        <x-settings.section title="Institution Identity" icon="domain">
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="Institution Name" name="institution_name" required>
                    <x-settings.input name="institution_name" maxlength="255" required
                        value="{{ old('institution_name', $s['institution_name']) }}"/>
                </x-settings.field>

                <x-settings.field label="Tagline" name="tagline">
                    <x-settings.input name="tagline" maxlength="255"
                        value="{{ old('tagline', $s['tagline']) }}" placeholder="A short institution motto"/>
                </x-settings.field>

                <x-settings.field label="Contact Email" name="contact_email">
                    <x-settings.input type="email" name="contact_email" maxlength="255"
                        value="{{ old('contact_email', $s['contact_email']) }}" placeholder="admin@example.edu"/>
                </x-settings.field>

                <x-settings.field label="Phone Number" name="phone">
                    <x-settings.input name="phone" data-mask="phone" maxlength="12"
                        value="{{ old('phone', $s['phone']) }}" placeholder="0300-0000000"/>
                </x-settings.field>

                <x-settings.field label="Institutional Address" name="address" class="md:col-span-2">
                    <x-settings.textarea name="address" rows="2" maxlength="1000"
                        placeholder="Street, city, region">{{ old('address', $s['address']) }}</x-settings.textarea>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- Branding Assets --}}
        <x-settings.section title="Branding Assets" icon="brush"
            desc="PNG, JPG, SVG or WEBP. Light/dark logos up to 5MB, favicon up to 1MB.">
            <div class="grid grid-cols-1 gap-lg md:grid-cols-3">
                @foreach ([
                    ['light_logo', 'Light Theme Logo', '.png,.jpg,.jpeg,.svg,.webp'],
                    ['dark_logo', 'Dark Theme Logo', '.png,.jpg,.jpeg,.svg,.webp'],
                    ['favicon', 'Favicon', '.png,.ico,.svg'],
                ] as [$field, $label, $accept])
                    <x-settings.field :label="$label" :name="$field">
                        @php $current = $s[$field] ?? null; @endphp
                        <div class="flex h-24 items-center justify-center overflow-hidden rounded-lg border-2 border-dashed border-outline-variant bg-surface-container-low">
                            @if ($current)
                                <img src="{{ Storage::url($current) }}" alt="{{ $label }}" class="max-h-full max-w-full object-contain p-2"/>
                            @else
                                <span class="material-symbols-outlined text-[28px] text-on-surface-variant">image</span>
                            @endif
                        </div>
                        <input type="file" name="{{ $field }}" accept="{{ $accept }}"
                               class="mt-2 block w-full text-label-sm text-on-surface-variant file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:font-bold file:text-on-primary hover:file:opacity-90"/>
                    </x-settings.field>
                @endforeach
            </div>
        </x-settings.section>

        {{-- System --}}
        <x-settings.section title="System" icon="tune">
            <div class="grid grid-cols-1 gap-lg md:grid-cols-2">
                <div class="rounded-lg border border-outline-variant p-4">
                    <x-settings.toggle name="maintenance_mode" label="Maintenance Mode"
                        desc="Take the app offline for non-admins."
                        :checked="old('maintenance_mode', $s['maintenance_mode'])"/>
                </div>
                <div class="rounded-lg border border-outline-variant p-4">
                    <x-settings.toggle name="debug_mode" label="Debug Mode"
                        desc="Show detailed errors (development only)."
                        :checked="old('debug_mode', $s['debug_mode'])"/>
                </div>
            </div>
        </x-settings.section>
    </x-settings.page>
@endsection
