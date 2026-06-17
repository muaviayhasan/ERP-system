@php
    use App\Http\Controllers\Admin\TimetableController;
    $tt = $timetable ?? null;
    $fk = fn ($field, $model) => (int) old($field, $tt->{$field} ?? 0) === $model->id;
    $start = old('week_start_date', isset($tt) ? $tt->week_start_date?->format('Y-m-d') : '');
    $end = old('week_end_date', isset($tt) ? $tt->week_end_date?->format('Y-m-d') : '');
@endphp

<x-settings.section title="Schedule Details" icon="calendar_month">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Schedule Name" name="name" class="md:col-span-2">
            <x-settings.input name="name" maxlength="255" value="{{ old('name', $tt->name ?? '') }}" placeholder="Fall 2024 — BS CS Semester 4"/>
        </x-settings.field>
        <x-settings.field label="Campus" name="campus_id">
            <x-settings.select name="campus_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($campuses as $campus)
                    <option value="{{ $campus->id }}" @selected($fk('campus_id', $campus))>{{ $campus->name }}</option>
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
        <x-settings.field label="Semester" name="semester_id">
            <x-settings.select name="semester_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($semesters as $semester)
                    <option value="{{ $semester->id }}" @selected($fk('semester_id', $semester))>{{ $semester->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Institute Type" name="institute_type">
            <x-settings.select name="institute_type" data-allow-clear placeholder="Select...">
                <option value="">Select...</option>
                @foreach (TimetableController::INSTITUTE_TYPES as $type)
                    <option value="{{ $type }}" @selected(old('institute_type', $tt->institute_type ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Week Start" name="week_start_date">
            <x-settings.input type="date" name="week_start_date" value="{{ $start }}"/>
        </x-settings.field>
        <x-settings.field label="Week End" name="week_end_date">
            <x-settings.input type="date" name="week_end_date" value="{{ $end }}"/>
        </x-settings.field>
    </div>
</x-settings.section>
