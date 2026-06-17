@php
    use App\Http\Controllers\Admin\SectionController;
    $sec = $section ?? null;
    $fk = fn ($field, $model) => (int) old($field, $sec->{$field} ?? 0) === $model->id;
@endphp

<x-settings.section title="Basic Information" icon="grid_view">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Section Name" name="name" required>
            <x-settings.input name="name" maxlength="255" required value="{{ old('name', $sec->name ?? '') }}" placeholder="Section A"/>
        </x-settings.field>
        <x-settings.field label="Section Code" name="code" required>
            <x-settings.input name="code" maxlength="255" required value="{{ old('code', $sec->code ?? '') }}" placeholder="010-SA-2024"/>
        </x-settings.field>
        <x-settings.field label="Section Type" name="section_type">
            <x-settings.select name="section_type" data-allow-clear placeholder="Select...">
                <option value="">Select...</option>
                @foreach (SectionController::TYPES as $type)
                    <option value="{{ $type }}" @selected(old('section_type', $sec->section_type ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (SectionController::STATUSES as $st)
                    <option value="{{ $st }}" @selected(old('status', $sec->status ?? 'active') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Hierarchy &amp; Placement" icon="account_tree">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Parent Class" name="class_id">
            <x-settings.select name="class_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}" @selected($fk('class_id', $class))>{{ $class->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Campus" name="campus_id">
            <x-settings.select name="campus_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($campuses as $campus)
                    <option value="{{ $campus->id }}" @selected($fk('campus_id', $campus))>{{ $campus->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Capacity &amp; Enrollment" icon="groups">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Max Capacity" name="max_capacity">
            <x-settings.input type="number" min="0" name="max_capacity" value="{{ old('max_capacity', $sec->max_capacity ?? 40) }}"/>
        </x-settings.field>
        <x-settings.field label="Current Enrollment" name="current_enrollment">
            <x-settings.input type="number" min="0" name="current_enrollment" value="{{ old('current_enrollment', $sec->current_enrollment ?? '') }}"/>
        </x-settings.field>
        <div class="rounded-lg border border-outline-variant p-4 md:col-span-2">
            <x-settings.toggle name="enable_waitlist" label="Enable Waitlist" :checked="old('enable_waitlist', $sec->enable_waitlist ?? true)"/>
        </div>
    </div>
</x-settings.section>

<x-settings.section title="Configuration" icon="tune">
    <div class="grid grid-cols-1 gap-md md:grid-cols-3">
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="is_active" label="Active" :checked="old('is_active', $sec->is_active ?? true)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="allow_admissions" label="Allow Admissions" :checked="old('allow_admissions', $sec->allow_admissions ?? true)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="lock_structure" label="Lock Structure" :checked="old('lock_structure', $sec->lock_structure ?? false)"/>
        </div>
    </div>
</x-settings.section>
