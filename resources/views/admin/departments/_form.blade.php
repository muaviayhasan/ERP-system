@php
    use App\Http\Controllers\Admin\DepartmentController;
    $d = $department ?? null;
    $selectedCampuses = array_map('strval', old('campuses', isset($d) ? $d->campuses->pluck('id')->all() : []));
@endphp

<x-settings.section title="Basic Information" icon="info">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Department Name" name="name" required>
            <x-settings.input name="name" maxlength="255" required value="{{ old('name', $d->name ?? '') }}" placeholder="Computer Science"/>
        </x-settings.field>
        <x-settings.field label="Department Code" name="code" required>
            <x-settings.input name="code" maxlength="255" required value="{{ old('code', $d->code ?? '') }}" placeholder="CS-001"/>
        </x-settings.field>
        <x-settings.field label="Institution Type" name="institution_type">
            <x-settings.select name="institution_type" data-allow-clear placeholder="Select a type...">
                <option value="">Select a type...</option>
                @foreach (DepartmentController::TYPES as $type)
                    <option value="{{ $type }}" @selected(old('institution_type', $d->institution_type ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Head of Department" name="hod_user_id">
            <x-settings.select name="hod_user_id" data-allow-clear placeholder="Assign a HOD...">
                <option value="">Unassigned</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected((int) old('hod_user_id', $d->hod_user_id ?? 0) === $user->id)>{{ $user->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Description" name="description" class="md:col-span-2">
            <x-settings.textarea name="description" rows="2">{{ old('description', $d->description ?? '') }}</x-settings.textarea>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Hierarchy" icon="account_tree" desc="Assign this department to one or more campuses.">
    <x-settings.field label="Campus Assignment" name="campuses">
        <x-settings.select name="campuses[]" multiple data-select2-parent placeholder="Select campuses...">
            @foreach ($campuses as $campus)
                <option value="{{ $campus->id }}" @selected(in_array((string) $campus->id, $selectedCampuses, true))>{{ $campus->name }}</option>
            @endforeach
        </x-settings.select>
    </x-settings.field>
</x-settings.section>

<x-settings.section title="Academic Structure" icon="tune">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="semester_system" label="Semester System" :checked="old('semester_system', $d->semester_system ?? true)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="credit_hour_system" label="Credit Hour System" :checked="old('credit_hour_system', $d->credit_hour_system ?? true)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="is_active" label="Active" :checked="old('is_active', $d->is_active ?? true)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="allow_admissions" label="Allow Admissions" :checked="old('allow_admissions', $d->allow_admissions ?? true)"/>
        </div>
    </div>
</x-settings.section>
