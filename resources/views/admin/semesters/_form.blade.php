@php
    use App\Http\Controllers\Admin\SemesterController;
    $sm = $semester ?? null;
    $fk = fn ($field, $model) => (int) old($field, $sm->{$field} ?? 0) === $model->id;
    $start = old('start_date', isset($sm) ? $sm->start_date?->format('Y-m-d') : '');
    $end = old('end_date', isset($sm) ? $sm->end_date?->format('Y-m-d') : '');
@endphp

<x-settings.section title="Basic Information" icon="date_range">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Semester Name" name="name" required>
            <x-settings.input name="name" maxlength="255" required value="{{ old('name', $sm->name ?? '') }}" placeholder="Fall 2025"/>
        </x-settings.field>
        <x-settings.field label="Semester Code" name="code" required>
            <x-settings.input name="code" maxlength="255" required value="{{ old('code', $sm->code ?? '') }}" placeholder="F25-CS-01"/>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (SemesterController::STATUSES as $st)
                    <option value="{{ $st }}" @selected(old('status', $sm->status ?? 'upcoming') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Grading System" name="grading_system">
            <x-settings.select name="grading_system" data-allow-clear placeholder="Select...">
                <option value="">Select...</option>
                @foreach (SemesterController::GRADING_SYSTEMS as $g)
                    <option value="{{ $g }}" @selected(old('grading_system', $sm->grading_system ?? '') === $g)>{{ $g }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Description" name="description" class="md:col-span-2">
            <x-settings.textarea name="description" rows="2">{{ old('description', $sm->description ?? '') }}</x-settings.textarea>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Linkage" icon="account_tree">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Program" name="program_id">
            <x-settings.select name="program_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}" @selected($fk('program_id', $program))>{{ $program->name }}</option>
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
        <x-settings.field label="Campus" name="campus_id">
            <x-settings.select name="campus_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($campuses as $campus)
                    <option value="{{ $campus->id }}" @selected($fk('campus_id', $campus))>{{ $campus->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Academic Year" name="academic_year_id">
            <x-settings.select name="academic_year_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($academicYears as $year)
                    <option value="{{ $year->id }}" @selected($fk('academic_year_id', $year))>{{ $year->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Timeline &amp; Structure" icon="schedule">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Start Date" name="start_date">
            <x-settings.input type="date" name="start_date" value="{{ $start }}"/>
        </x-settings.field>
        <x-settings.field label="End Date" name="end_date">
            <x-settings.input type="date" name="end_date" value="{{ $end }}"/>
        </x-settings.field>
        <x-settings.field label="Total Credit Hours" name="total_credit_hours">
            <x-settings.input type="number" min="0" name="total_credit_hours" value="{{ old('total_credit_hours', $sm->total_credit_hours ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Late Fee Rule" name="late_fee_rule">
            <x-settings.input name="late_fee_rule" maxlength="255" value="{{ old('late_fee_rule', $sm->late_fee_rule ?? '') }}" placeholder="e.g. 2% per week"/>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Cycle Configuration" icon="tune">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="generate_fee_plan" label="Generate Fee Plan" :checked="old('generate_fee_plan', $sm->generate_fee_plan ?? true)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="is_locked" label="Lock Semester" :checked="old('is_locked', $sm->is_locked ?? false)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="fee_cycle_generated" label="Fee Cycle Generated" :checked="old('fee_cycle_generated', $sm->fee_cycle_generated ?? false)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="exam_cycle_generated" label="Exam Cycle Generated" :checked="old('exam_cycle_generated', $sm->exam_cycle_generated ?? false)"/>
        </div>
    </div>
</x-settings.section>
