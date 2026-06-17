@php
    use App\Http\Controllers\Admin\ProgramController;
    $p = $program ?? null;
    $selectedCampuses = array_map('strval', old('campuses', isset($p) ? $p->campuses->pluck('id')->all() : []));
@endphp

<x-settings.section title="Basic Information" icon="school">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Program Full Name" name="name" required>
            <x-settings.input name="name" maxlength="255" required value="{{ old('name', $p->name ?? '') }}" placeholder="BS Artificial Intelligence"/>
        </x-settings.field>
        <x-settings.field label="Program Code" name="code" required>
            <x-settings.input name="code" maxlength="255" required value="{{ old('code', $p->code ?? '') }}" placeholder="AI-UG-2024"/>
        </x-settings.field>
        <x-settings.field label="Degree Level" name="degree_level">
            <x-settings.select name="degree_level" data-allow-clear placeholder="Select a level...">
                <option value="">Select a level...</option>
                @foreach (ProgramController::DEGREE_LEVELS as $level)
                    <option value="{{ $level }}" @selected(old('degree_level', $p->degree_level ?? '') === $level)>{{ $level }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (ProgramController::STATUSES as $st)
                    <option value="{{ $st }}" @selected(old('status', $p->status ?? 'active') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Academic Linkage" icon="account_tree">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Primary Department" name="department_id">
            <x-settings.select name="department_id" data-allow-clear placeholder="Select a department...">
                <option value="">Unassigned</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @selected((int) old('department_id', $p->department_id ?? 0) === $department->id)>{{ $department->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Coordinator" name="coordinator_user_id">
            <x-settings.select name="coordinator_user_id" data-allow-clear placeholder="Assign a coordinator...">
                <option value="">Unassigned</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected((int) old('coordinator_user_id', $p->coordinator_user_id ?? 0) === $user->id)>{{ $user->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Faculty" name="faculty">
            <x-settings.input name="faculty" maxlength="255" value="{{ old('faculty', $p->faculty ?? '') }}" placeholder="Faculty of IT"/>
        </x-settings.field>
        <x-settings.field label="Assigned Campuses" name="campuses">
            <x-settings.select name="campuses[]" multiple data-select2-parent placeholder="Select campuses...">
                @foreach ($campuses as $campus)
                    <option value="{{ $campus->id }}" @selected(in_array((string) $campus->id, $selectedCampuses, true))>{{ $campus->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <div class="rounded-lg border border-outline-variant p-4 md:col-span-2">
            <x-settings.toggle name="multi_department_access" label="Multi-department Access"
                desc="Allow courses from other departments." :checked="old('multi_department_access', $p->multi_department_access ?? false)"/>
        </div>
    </div>
</x-settings.section>

<x-settings.section title="Duration &amp; Structure" icon="straighten">
    <div class="grid grid-cols-1 gap-md md:grid-cols-3">
        <x-settings.field label="Total Years" name="total_years">
            <x-settings.input type="number" step="0.5" min="0" name="total_years" value="{{ old('total_years', $p->total_years ?? '') }}" placeholder="4"/>
        </x-settings.field>
        <x-settings.field label="Total Semesters" name="total_semesters">
            <x-settings.input type="number" min="0" name="total_semesters" value="{{ old('total_semesters', $p->total_semesters ?? '') }}" placeholder="8"/>
        </x-settings.field>
        <x-settings.field label="Total Credits" name="total_credits">
            <x-settings.input type="number" min="0" name="total_credits" value="{{ old('total_credits', $p->total_credits ?? '') }}" placeholder="136"/>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Administrative Controls" icon="admin_panel_settings">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="allow_admissions" label="Allow Admissions" desc="Enable portal registration." :checked="old('allow_admissions', $p->allow_admissions ?? true)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="lock_structure" label="Lock Structure" desc="Prevent curriculum edits." :checked="old('lock_structure', $p->lock_structure ?? false)"/>
        </div>
    </div>
</x-settings.section>
