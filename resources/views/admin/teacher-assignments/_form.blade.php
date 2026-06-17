@php
    use App\Http\Controllers\Admin\TeacherAssignmentController;
    $a = $assignment ?? null;
    $fk = fn ($field, $model) => (int) old($field, $a->{$field} ?? 0) === $model->id;
@endphp

<x-settings.section title="Assignment" icon="assignment_ind">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Teacher" name="teacher_id">
            <x-settings.select name="teacher_id" data-allow-clear placeholder="Select a teacher...">
                <option value="">Unassigned</option>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}" @selected($fk('teacher_id', $teacher))>{{ $teacher->full_name }} ({{ $teacher->teacher_code }})</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Institute Type" name="institute_type">
            <x-settings.select name="institute_type" data-allow-clear placeholder="Select...">
                <option value="">Select...</option>
                @foreach (['School', 'College', 'University', 'Academy'] as $type)
                    <option value="{{ $type }}" @selected(old('institute_type', $a->institute_type ?? '') === $type)>{{ $type }}</option>
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
        <x-settings.field label="Program" name="program_id">
            <x-settings.select name="program_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}" @selected($fk('program_id', $program))>{{ $program->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Course" name="course_id">
            <x-settings.select name="course_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected($fk('course_id', $course))>{{ $course->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Subject" name="subject_id">
            <x-settings.select name="subject_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected($fk('subject_id', $subject))>{{ $subject->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Section" name="section_id">
            <x-settings.select name="section_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}" @selected($fk('section_id', $section))>{{ $section->name }}</option>
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
    </div>
</x-settings.section>

<x-settings.section title="Workload &amp; Timetable" icon="schedule">
    <div class="grid grid-cols-2 gap-md md:grid-cols-4">
        <x-settings.field label="Credits" name="credits">
            <x-settings.input name="credits" maxlength="255" value="{{ old('credits', $a->credits ?? '') }}" placeholder="3"/>
        </x-settings.field>
        <x-settings.field label="Lecture Hrs" name="lecture_hours">
            <x-settings.input type="number" step="0.5" min="0" name="lecture_hours" value="{{ old('lecture_hours', $a->lecture_hours ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Lab Hrs" name="lab_hours">
            <x-settings.input type="number" step="0.5" min="0" name="lab_hours" value="{{ old('lab_hours', $a->lab_hours ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Weekly Hrs" name="weekly_hours">
            <x-settings.input type="number" step="0.5" min="0" name="weekly_hours" value="{{ old('weekly_hours', $a->weekly_hours ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Max Weekly Hrs" name="max_weekly_hours">
            <x-settings.input type="number" step="0.5" min="0" name="max_weekly_hours" value="{{ old('max_weekly_hours', $a->max_weekly_hours ?? 40) }}"/>
        </x-settings.field>
        <x-settings.field label="Timetable Status" name="timetable_status">
            <x-settings.select name="timetable_status">
                @foreach (TeacherAssignmentController::TIMETABLE_STATUSES as $ts)
                    <option value="{{ $ts }}" @selected(old('timetable_status', $a->timetable_status ?? 'pending') === $ts)>{{ ucfirst($ts) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (TeacherAssignmentController::STATUSES as $st)
                    <option value="{{ $st }}" @selected(old('status', $a->status ?? 'active') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
    </div>
    <div class="mt-md rounded-lg border border-outline-variant p-4">
        <x-settings.toggle name="has_conflict" label="Flag Timetable Conflict"
            desc="Mark this assignment as clashing with another slot." :checked="old('has_conflict', $a->has_conflict ?? false)"/>
    </div>
    <x-settings.field label="Conflict Note" name="conflict_note" class="mt-md">
        <x-settings.input name="conflict_note" maxlength="255" value="{{ old('conflict_note', $a->conflict_note ?? '') }}" placeholder="Room conflict detected for Lab-04 on Monday at 10:30 AM"/>
    </x-settings.field>
</x-settings.section>
