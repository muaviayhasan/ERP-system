@php
    use App\Http\Controllers\Admin\FeeCategoryController;
    $c = $category ?? null;
    $typeLabels = ['one_time' => 'One-time', 'monthly' => 'Monthly', 'annual' => 'Annual', 'semester_based' => 'Semester-based', 'quarterly' => 'Quarterly'];
@endphp

<x-settings.section title="Basic Information" icon="category">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Name" name="name" required>
            <x-settings.input name="name" maxlength="255" required value="{{ old('name', $c->name ?? '') }}" placeholder="Tuition Fee"/>
        </x-settings.field>
        <x-settings.field label="Code" name="code" required>
            <x-settings.input name="code" maxlength="255" required value="{{ old('code', $c->code ?? '') }}" placeholder="TUIT"/>
        </x-settings.field>
        <x-settings.field label="Fee Type" name="fee_type" required>
            <x-settings.select name="fee_type" required>
                @foreach (FeeCategoryController::FEE_TYPES as $type)
                    <option value="{{ $type }}" @selected(old('fee_type', $c->fee_type ?? '') === $type)>{{ $typeLabels[$type] }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (FeeCategoryController::STATUSES as $st)
                    <option value="{{ $st }}" @selected(old('status', $c->status ?? 'active') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Default Amount" name="default_amount">
            <x-settings.input type="number" step="0.01" min="0" name="default_amount" value="{{ old('default_amount', $c->default_amount ?? 0) }}"/>
        </x-settings.field>
        <x-settings.field label="Currency" name="currency">
            <x-settings.input name="currency" maxlength="10" value="{{ old('currency', $c->currency ?? 'PKR') }}"/>
        </x-settings.field>
        <x-settings.field label="Description" name="description" class="md:col-span-2">
            <x-settings.textarea name="description" rows="2">{{ old('description', $c->description ?? '') }}</x-settings.textarea>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Applicability" icon="domain">
    <div class="grid grid-cols-1 gap-md md:grid-cols-3">
        @foreach (['applies_to_school' => 'School', 'applies_to_college' => 'College', 'applies_to_university' => 'University'] as $field => $label)
            <div class="rounded-lg border border-outline-variant p-4">
                <x-settings.toggle :name="$field" :label="$label" :checked="old($field, $c->{$field} ?? false)"/>
            </div>
        @endforeach
    </div>
</x-settings.section>

<x-settings.section title="Late Fee, Tax &amp; Flags" icon="tune">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="late_fee_enabled" label="Late Fee" desc="Charge a penalty after the grace period." :checked="old('late_fee_enabled', $c->late_fee_enabled ?? false)"/>
        </div>
        <x-settings.field label="Late Fee Amount" name="late_fee_amount">
            <x-settings.input type="number" step="0.01" min="0" name="late_fee_amount" value="{{ old('late_fee_amount', $c->late_fee_amount ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Grace Period (days)" name="grace_period_days">
            <x-settings.input type="number" min="0" name="grace_period_days" value="{{ old('grace_period_days', $c->grace_period_days ?? 0) }}"/>
        </x-settings.field>
        <x-settings.field label="Tax Percentage" name="tax_percentage">
            <x-settings.input type="number" step="0.01" min="0" max="100" name="tax_percentage" value="{{ old('tax_percentage', $c->tax_percentage ?? '') }}"/>
        </x-settings.field>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="tax_applicable" label="Tax Applicable" :checked="old('tax_applicable', $c->tax_applicable ?? false)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="scholarship_eligible" label="Scholarship Eligible" :checked="old('scholarship_eligible', $c->scholarship_eligible ?? false)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="refundable" label="Refundable" :checked="old('refundable', $c->refundable ?? false)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="auto_generate_on_admission" label="Auto-generate on Admission" :checked="old('auto_generate_on_admission', $c->auto_generate_on_admission ?? false)"/>
        </div>
    </div>
</x-settings.section>
