@php
    use App\Http\Controllers\Admin\CampusController;

    $inputClass = 'w-full rounded-lg border border-outline-variant bg-white px-md py-2.5 text-body-md outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20';
    $c = $campus ?? null;
@endphp

<div class="space-y-lg">
    {{-- Basic Information --}}
    <section class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
        <div class="mb-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">info</span>
            <h3 class="text-label-md font-bold uppercase tracking-widest text-primary">Basic Information</h3>
        </div>
        <div class="grid grid-cols-1 gap-md md:grid-cols-2">
            <div class="space-y-1 md:col-span-2">
                <label class="text-label-sm font-bold text-on-surface-variant">Campus Name <span class="text-error">*</span></label>
                <input type="text" name="name" value="{{ old('name', $c->name ?? '') }}" maxlength="255" required
                       placeholder="e.g. West Coast Medical College" class="{{ $inputClass }}"/>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Campus Code <span class="text-error">*</span></label>
                <input type="text" name="code" value="{{ old('code', $c->code ?? '') }}" maxlength="255" required
                       placeholder="WCMC-001" class="{{ $inputClass }}"/>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Institution Type</label>
                <select name="institution_type" data-allow-clear placeholder="Select a type..." class="{{ $inputClass }}">
                    <option value="">Select a type...</option>
                    @foreach (CampusController::TYPES as $type)
                        <option value="{{ $type }}" @selected(old('institution_type', $c->institution_type ?? '') === $type)>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Founded Year</label>
                <input type="number" name="founded_year" value="{{ old('founded_year', $c->founded_year ?? '') }}"
                       min="1800" max="{{ date('Y') }}" placeholder="1998" class="{{ $inputClass }}"/>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Status</label>
                <select name="status" class="{{ $inputClass }}">
                    @foreach (CampusController::STATUSES as $st)
                        <option value="{{ $st }}" @selected(old('status', $c->status ?? 'active') === $st)>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1 md:col-span-2">
                <label class="text-label-sm font-bold text-on-surface-variant">Description</label>
                <textarea name="description" rows="3" placeholder="Brief overview of the campus specialization..."
                          class="{{ $inputClass }}">{{ old('description', $c->description ?? '') }}</textarea>
            </div>
        </div>
    </section>

    {{-- Location --}}
    <section class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
        <div class="mb-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">location_on</span>
            <h3 class="text-label-md font-bold uppercase tracking-widest text-primary">Location Details</h3>
        </div>
        <div class="grid grid-cols-1 gap-md md:grid-cols-2">
            <div class="space-y-1 md:col-span-2">
                <label class="text-label-sm font-bold text-on-surface-variant">Street Address</label>
                <input type="text" name="street_address" value="{{ old('street_address', $c->street_address ?? '') }}"
                       maxlength="255" placeholder="123 Academic Way" class="{{ $inputClass }}"/>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">City</label>
                <input type="text" name="city" value="{{ old('city', $c->city ?? '') }}" maxlength="255"
                       placeholder="San Francisco" class="{{ $inputClass }}"/>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">State / Province</label>
                <input type="text" name="state_province" value="{{ old('state_province', $c->state_province ?? '') }}"
                       maxlength="255" placeholder="California" class="{{ $inputClass }}"/>
            </div>
        </div>
    </section>

    {{-- Academic & Operational Config --}}
    <section class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
        <div class="mb-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">settings</span>
            <h3 class="text-label-md font-bold uppercase tracking-widest text-primary">Academic &amp; Operational Config</h3>
        </div>
        <div class="space-y-sm rounded-xl bg-surface-container-low p-md">
            <div class="rounded-lg p-2">
                <x-settings.toggle name="enable_online_admissions" label="Enable Online Admissions"
                    desc="Allow students to apply via the public portal."
                    :checked="old('enable_online_admissions', $c->enable_online_admissions ?? true)"/>
            </div>
            <div class="rounded-lg p-2">
                <x-settings.toggle name="centralized_fee_collection" label="Centralized Fee Collection"
                    desc="Share bank details with the main institute."
                    :checked="old('centralized_fee_collection', $c->centralized_fee_collection ?? false)"/>
            </div>
            <div class="rounded-lg p-2">
                <x-settings.toggle name="hostel_management" label="Hostel Management"
                    desc="Enable residence and boarding modules."
                    :checked="old('hostel_management', $c->hostel_management ?? false)"/>
            </div>
        </div>
    </section>

    {{-- Financial --}}
    <section class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
        <div class="mb-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">account_balance</span>
            <h3 class="text-label-md font-bold uppercase tracking-widest text-primary">Financial Settings</h3>
        </div>
        <div class="grid grid-cols-1 gap-md md:grid-cols-2">
            <div class="space-y-1 md:col-span-2">
                <label class="text-label-sm font-bold text-on-surface-variant">Primary Bank Name</label>
                <input type="text" name="primary_bank_name" value="{{ old('primary_bank_name', $c->primary_bank_name ?? '') }}"
                       maxlength="255" placeholder="Global Trust Bank" class="{{ $inputClass }}"/>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Account Number</label>
                <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $c->bank_account_number ?? '') }}"
                       maxlength="255" autocomplete="off" class="{{ $inputClass }}"/>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">IFSC / SWIFT Code</label>
                <input type="text" name="bank_swift_code" value="{{ old('bank_swift_code', $c->bank_swift_code ?? '') }}"
                       maxlength="255" placeholder="GTB000122" class="{{ $inputClass }}"/>
            </div>
        </div>
    </section>
</div>

<div class="mt-lg flex items-center justify-end gap-3">
    <a href="{{ route('campuses.index') }}" class="rounded-lg border border-outline-variant px-lg py-2.5 font-bold text-on-surface-variant transition-colors hover:bg-surface-container-low">Cancel</a>
    <button type="submit" class="rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
        {{ isset($campus) ? 'Update Campus' : 'Save Campus' }}
    </button>
</div>
