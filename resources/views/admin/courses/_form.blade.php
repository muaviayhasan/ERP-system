@php
    use App\Http\Controllers\Admin\CourseController;
    $c = $course ?? null;
    $fk = fn ($field, $model) => (int) old($field, $c->{$field} ?? 0) === $model->id;
@endphp

<x-settings.section title="Basic Information" icon="menu_book">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Course Name" name="name" required>
            <x-settings.input name="name" maxlength="255" required value="{{ old('name', $c->name ?? '') }}" placeholder="Intro to Programming"/>
        </x-settings.field>
        <x-settings.field label="Course Code" name="code" required>
            <x-settings.input name="code" maxlength="255" required value="{{ old('code', $c->code ?? '') }}" placeholder="CS-101"/>
        </x-settings.field>
        <x-settings.field label="Type" name="type">
            <x-settings.select name="type" data-allow-clear placeholder="Select a type...">
                <option value="">Select a type...</option>
                @foreach (CourseController::TYPES as $type)
                    <option value="{{ $type }}" @selected(old('type', $c->type ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (CourseController::STATUSES as $st)
                    <option value="{{ $st }}" @selected(old('status', $c->status ?? 'active') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Description" name="description" class="md:col-span-2">
            <x-settings.textarea name="description" rows="2">{{ old('description', $c->description ?? '') }}</x-settings.textarea>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Academic Linkage" icon="account_tree">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Program" name="program_id">
            <x-settings.select name="program_id" data-allow-clear placeholder="Select a program...">
                <option value="">Unassigned</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}" @selected($fk('program_id', $program))>{{ $program->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Department" name="department_id">
            <x-settings.select name="department_id" data-allow-clear placeholder="Select a department...">
                <option value="">Unassigned</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @selected($fk('department_id', $department))>{{ $department->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Campus" name="campus_id">
            <x-settings.select name="campus_id" data-allow-clear placeholder="Select a campus...">
                <option value="">Unassigned</option>
                @foreach ($campuses as $campus)
                    <option value="{{ $campus->id }}" @selected($fk('campus_id', $campus))>{{ $campus->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Semester" name="semester_id">
            <x-settings.select name="semester_id" data-allow-clear placeholder="Select a semester...">
                <option value="">Unassigned</option>
                @foreach ($semesters as $semester)
                    <option value="{{ $semester->id }}" @selected($fk('semester_id', $semester))>{{ $semester->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Credit &amp; Assessment" icon="grade">
    <div class="grid grid-cols-2 gap-md md:grid-cols-4">
        <x-settings.field label="Credit Hours" name="credit_hours">
            <x-settings.input type="number" min="0" name="credit_hours" value="{{ old('credit_hours', $c->credit_hours ?? '') }}" placeholder="3"/>
        </x-settings.field>
        <x-settings.field label="Total Marks" name="total_marks">
            <x-settings.input type="number" min="0" name="total_marks" value="{{ old('total_marks', $c->total_marks ?? 100) }}"/>
        </x-settings.field>
        <x-settings.field label="Passing %" name="passing_percentage">
            <x-settings.input type="number" min="0" max="100" name="passing_percentage" value="{{ old('passing_percentage', $c->passing_percentage ?? 50) }}"/>
        </x-settings.field>
        <x-settings.field label="Quiz %" name="weight_quiz">
            <x-settings.input type="number" min="0" max="100" name="weight_quiz" value="{{ old('weight_quiz', $c->weight_quiz ?? 10) }}"/>
        </x-settings.field>
        <x-settings.field label="Assignment %" name="weight_assignment">
            <x-settings.input type="number" min="0" max="100" name="weight_assignment" value="{{ old('weight_assignment', $c->weight_assignment ?? 15) }}"/>
        </x-settings.field>
        <x-settings.field label="Mid %" name="weight_mid">
            <x-settings.input type="number" min="0" max="100" name="weight_mid" value="{{ old('weight_mid', $c->weight_mid ?? 25) }}"/>
        </x-settings.field>
        <x-settings.field label="Final %" name="weight_final">
            <x-settings.input type="number" min="0" max="100" name="weight_final" value="{{ old('weight_final', $c->weight_final ?? 50) }}"/>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Configuration" icon="tune">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="is_active" label="Active" :checked="old('is_active', $c->is_active ?? true)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="open_enrollment" label="Open Enrollment" :checked="old('open_enrollment', $c->open_enrollment ?? true)"/>
        </div>
    </div>
</x-settings.section>
