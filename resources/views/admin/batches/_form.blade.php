@php
    use App\Http\Controllers\Admin\BatchController;
    $b = $batch ?? null;
    $fk = fn ($field, $model) => (int) old($field, $b->{$field} ?? 0) === $model->id;
    $start = old('start_date', isset($b) ? $b->start_date?->format('Y-m-d') : '');
    $end = old('end_date', isset($b) ? $b->end_date?->format('Y-m-d') : '');
    $selectedDays = (array) old('weekly_days', $b->weekly_days ?? []);
@endphp

<x-settings.section title="Basic Information" icon="diversity_3">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Batch Name" name="name" required>
            <x-settings.input name="name" maxlength="255" required value="{{ old('name', $b->name ?? '') }}" placeholder="Morning Batch 2026-A"/>
        </x-settings.field>
        <x-settings.field label="Batch Code" name="code" required>
            <x-settings.input name="code" maxlength="255" required value="{{ old('code', $b->code ?? '') }}" placeholder="CS-2026-M1"/>
        </x-settings.field>
        <x-settings.field label="Batch Type" name="batch_type">
            <x-settings.select name="batch_type" data-allow-clear placeholder="Select...">
                <option value="">Select...</option>
                @foreach (BatchController::TYPES as $type)
                    <option value="{{ $type }}" @selected(old('batch_type', $b->batch_type ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (BatchController::STATUSES as $st)
                    <option value="{{ $st }}" @selected(old('status', $b->status ?? 'active') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Description" name="description" class="md:col-span-2">
            <x-settings.textarea name="description" rows="2">{{ old('description', $b->description ?? '') }}</x-settings.textarea>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Classification &amp; Academic Link" icon="account_tree">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Institution Type" name="institution_type">
            <x-settings.select name="institution_type" data-allow-clear placeholder="Select...">
                <option value="">Select...</option>
                @foreach (['University', 'College', 'School', 'Vocational'] as $type)
                    <option value="{{ $type }}" @selected(old('institution_type', $b->institution_type ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Program" name="program_id">
            <x-settings.select name="program_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}" @selected($fk('program_id', $program))>{{ $program->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Class" name="class_id">
            <x-settings.select name="class_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}" @selected($fk('class_id', $class))>{{ $class->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Semester" name="semester_id">
            <x-settings.select name="semester_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($semesters as $semester)
                    <option value="{{ $semester->id }}" @selected($fk('semester_id', $semester))>{{ $semester->name }}</option>
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

<x-settings.section title="Schedule Configuration" icon="schedule">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Start Date" name="start_date">
            <x-settings.input type="date" name="start_date" value="{{ $start }}"/>
        </x-settings.field>
        <x-settings.field label="End Date" name="end_date">
            <x-settings.input type="date" name="end_date" value="{{ $end }}"/>
        </x-settings.field>
    </div>
    <x-settings.field label="Weekly Days" name="weekly_days" class="mt-md">
        <div class="flex flex-wrap gap-2">
            @foreach (BatchController::WEEK_DAYS as $day)
                <label class="cursor-pointer">
                    <input type="checkbox" name="weekly_days[]" value="{{ $day }}" class="peer sr-only" @checked(in_array($day, $selectedDays, true))>
                    <span class="inline-flex h-9 w-12 items-center justify-center rounded-full border border-outline-variant text-label-md text-on-surface-variant transition-colors peer-checked:border-primary peer-checked:bg-primary peer-checked:text-on-primary">{{ $day }}</span>
                </label>
            @endforeach
        </div>
    </x-settings.field>
</x-settings.section>

<x-settings.section title="Capacity &amp; Faculty" icon="groups">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Max Students" name="max_students">
            <x-settings.input type="number" min="0" name="max_students" value="{{ old('max_students', $b->max_students ?? 40) }}"/>
        </x-settings.field>
        <x-settings.field label="Attendance Tracking" name="attendance_tracking">
            <x-settings.select name="attendance_tracking" data-allow-clear placeholder="Select...">
                <option value="">Select...</option>
                @foreach (['Daily', 'Per-Session', 'Manual'] as $mode)
                    <option value="{{ $mode }}" @selected(old('attendance_tracking', $b->attendance_tracking ?? '') === $mode)>{{ $mode }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="allow_waitlist" label="Allow Waitlist" :checked="old('allow_waitlist', $b->allow_waitlist ?? true)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="installments_allowed" label="Installments Allowed" :checked="old('installments_allowed', $b->installments_allowed ?? true)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4 md:col-span-2">
            <x-settings.toggle name="open_for_admissions" label="Open for Admissions" :checked="old('open_for_admissions', $b->open_for_admissions ?? true)"/>
        </div>
    </div>
</x-settings.section>
