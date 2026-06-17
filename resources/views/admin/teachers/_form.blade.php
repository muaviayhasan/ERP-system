@php
    use App\Http\Controllers\Admin\TeacherController;
    $t = $teacher ?? null;
    $fk = fn ($field, $model) => (int) old($field, $t->{$field} ?? 0) === $model->id;
    $selectedPrograms = array_map('strval', old('programs', isset($t) ? $t->programs->pluck('id')->all() : []));
    $joining = old('joining_date', isset($t) ? $t->joining_date?->format('Y-m-d') : '');
@endphp

<x-settings.section title="Basic Information" icon="badge">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="First Name" name="first_name" required>
            <x-settings.input name="first_name" maxlength="255" required value="{{ old('first_name', $t->first_name ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Last Name" name="last_name" required>
            <x-settings.input name="last_name" maxlength="255" required value="{{ old('last_name', $t->last_name ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Teacher Code" name="teacher_code" required>
            <x-settings.input name="teacher_code" maxlength="255" required value="{{ old('teacher_code', $t->teacher_code ?? '') }}" placeholder="TCH-2024-042"/>
        </x-settings.field>
        <x-settings.field label="Designation" name="designation" required>
            <x-settings.input name="designation" maxlength="255" required list="designation-list" value="{{ old('designation', $t->designation ?? '') }}" placeholder="Professor"/>
            <datalist id="designation-list">
                @foreach (TeacherController::DESIGNATIONS as $d)<option value="{{ $d }}">@endforeach
            </datalist>
        </x-settings.field>
        <x-settings.field label="Email" name="email" required>
            <x-settings.input type="email" name="email" maxlength="255" required value="{{ old('email', $t->email ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Phone" name="phone">
            <x-settings.input name="phone" data-mask="phone" maxlength="12" value="{{ old('phone', $t->phone ?? '') }}" placeholder="0300-0000000"/>
        </x-settings.field>
        <x-settings.field label="CNIC" name="cnic">
            <x-settings.input name="cnic" data-mask="cnic" maxlength="15" value="{{ old('cnic', $t->cnic ?? '') }}" placeholder="32301-0000000-0"/>
        </x-settings.field>
        <x-settings.field label="Joining Date" name="joining_date">
            <x-settings.input type="date" name="joining_date" value="{{ $joining }}"/>
        </x-settings.field>
        <x-settings.field label="Photo" name="photo" class="md:col-span-2">
            @if ($t?->photo_url)
                <div class="mb-2 flex items-center gap-3">
                    <img src="{{ Storage::url($t->photo_url) }}" alt="" class="h-12 w-12 rounded-full object-cover"/>
                    <span class="text-label-sm text-on-surface-variant">Current photo — upload to replace.</span>
                </div>
            @endif
            <input type="file" name="photo" accept="image/*"
                   class="block w-full text-label-sm text-on-surface-variant file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:font-bold file:text-on-primary hover:file:opacity-90"/>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Academic Assignment" icon="account_tree">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Campus" name="campus_id">
            <x-settings.select name="campus_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($campuses as $campus)
                    <option value="{{ $campus->id }}" @selected($fk('campus_id', $campus))>{{ $campus->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Department" name="department_id">
            <x-settings.select name="department_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @selected($fk('department_id', $department))>{{ $department->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Institute Type" name="institute_type">
            <x-settings.select name="institute_type" data-allow-clear placeholder="Select...">
                <option value="">Select...</option>
                @foreach (TeacherController::INSTITUTE_TYPES as $type)
                    <option value="{{ $type }}" @selected(old('institute_type', $t->institute_type ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (TeacherController::STATUSES as $st)
                    <option value="{{ $st }}" @selected(old('status', $t->status ?? 'active') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Assigned Programs" name="programs" class="md:col-span-2">
            <x-settings.select name="programs[]" multiple data-select2-parent placeholder="Select programs...">
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}" @selected(in_array((string) $program->id, $selectedPrograms, true))>{{ $program->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Workload" icon="schedule">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Weekly Workload (hrs)" name="weekly_workload_hours">
            <x-settings.input type="number" step="0.5" min="0" name="weekly_workload_hours" value="{{ old('weekly_workload_hours', $t->weekly_workload_hours ?? '') }}" placeholder="22"/>
        </x-settings.field>
        <x-settings.field label="Max Workload (hrs)" name="max_workload_hours">
            <x-settings.input type="number" step="0.5" min="0" name="max_workload_hours" value="{{ old('max_workload_hours', $t->max_workload_hours ?? 40) }}"/>
        </x-settings.field>
    </div>
</x-settings.section>
