@php
    use App\Http\Controllers\Admin\GuardianController;
    $g = $guardian ?? null;
    $selectedStudents = array_map('strval', old('students', isset($g) ? $g->students->pluck('id')->all() : []));
@endphp

<x-settings.section title="Basic Information" icon="badge">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Full Name" name="full_name" required>
            <x-settings.input name="full_name" maxlength="255" required value="{{ old('full_name', $g->full_name ?? '') }}" placeholder="Jonathan Doe"/>
        </x-settings.field>
        <x-settings.field label="Relationship" name="relationship">
            <x-settings.select name="relationship" data-allow-clear placeholder="Select...">
                <option value="">Select...</option>
                @foreach (GuardianController::RELATIONSHIPS as $rel)
                    <option value="{{ $rel }}" @selected(old('relationship', $g->relationship ?? '') === $rel)>{{ ucfirst($rel) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="CNIC / Passport" name="cnic">
            <x-settings.input name="cnic" data-mask="cnic" maxlength="15" value="{{ old('cnic', $g->cnic ?? '') }}" placeholder="42xxx-xxxxxxx-x"/>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (GuardianController::STATUSES as $st)
                    <option value="{{ $st }}" @selected(old('status', $g->status ?? 'active') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Contact Details" icon="call">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Phone Number" name="phone" required>
            <x-settings.input name="phone" data-mask="phone" maxlength="12" required value="{{ old('phone', $g->phone ?? '') }}" placeholder="0300-0000000"/>
        </x-settings.field>
        <x-settings.field label="Email Address" name="email">
            <x-settings.input type="email" name="email" maxlength="255" value="{{ old('email', $g->email ?? '') }}" placeholder="name@domain.com"/>
        </x-settings.field>
        <x-settings.field label="Residential Address" name="residential_address" class="md:col-span-2">
            <x-settings.textarea name="residential_address" rows="2" placeholder="Enter full address...">{{ old('residential_address', $g->residential_address ?? '') }}</x-settings.textarea>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Student Linkage" icon="link" desc="Attach the children/wards this guardian is responsible for.">
    <x-settings.field label="Linked Students" name="students">
        <x-settings.select name="students[]" multiple data-select2-parent placeholder="Link students...">
            @foreach ($students as $s)
                <option value="{{ $s->id }}" @selected(in_array((string) $s->id, $selectedStudents, true))>{{ $s->full_name }} ({{ $s->student_code }})</option>
            @endforeach
        </x-settings.select>
        @if ($students->isEmpty())
            <p class="text-label-sm text-on-surface-variant">No students yet — <a href="{{ route('students.create') }}" class="text-primary hover:underline">admit one first</a>.</p>
        @endif
    </x-settings.field>
</x-settings.section>

<x-settings.section title="Preferences &amp; Finance" icon="account_balance_wallet">
    <div class="grid grid-cols-1 gap-md md:grid-cols-3">
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="is_primary_fee_payer" label="Primary Fee Payer"
                desc="Responsible for all billing invoices." :checked="old('is_primary_fee_payer', $g->is_primary_fee_payer ?? false)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="is_emergency_authorized" label="Emergency Authorized"
                desc="Can pick up student from campus." :checked="old('is_emergency_authorized', $g->is_emergency_authorized ?? false)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="phone_verified" label="Phone Verified"
                desc="Contact number confirmed." :checked="old('phone_verified', $g->phone_verified ?? false)"/>
        </div>
    </div>
</x-settings.section>
