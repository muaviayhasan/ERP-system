@php
    use App\Http\Controllers\Admin\SubjectController;
    $s = $subject ?? null;
    $fk = fn ($field, $model) => (int) old($field, $s->{$field} ?? 0) === $model->id;
@endphp

<x-settings.section title="Basic Information" icon="book_2">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Subject Name" name="name" required>
            <x-settings.input name="name" maxlength="255" required value="{{ old('name', $s->name ?? '') }}" placeholder="Modern Web Architecture"/>
        </x-settings.field>
        <x-settings.field label="Subject Code" name="code" required>
            <x-settings.input name="code" maxlength="255" required value="{{ old('code', $s->code ?? '') }}" placeholder="CODE-123"/>
        </x-settings.field>
        <x-settings.field label="Classification" name="classification">
            <x-settings.select name="classification" data-allow-clear placeholder="Select...">
                <option value="">Select...</option>
                @foreach (SubjectController::CLASSIFICATIONS as $type)
                    <option value="{{ $type }}" @selected(old('classification', $s->classification ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Institution Type" name="institution_type">
            <x-settings.select name="institution_type" data-allow-clear placeholder="Select...">
                <option value="">Select...</option>
                @foreach (SubjectController::TYPES as $type)
                    <option value="{{ $type }}" @selected(old('institution_type', $s->institution_type ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Academic Linkage" icon="account_tree">
    <div class="grid grid-cols-1 gap-md md:grid-cols-3">
        <x-settings.field label="Department" name="department_id">
            <x-settings.select name="department_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @selected($fk('department_id', $department))>{{ $department->name }}</option>
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
    </div>
</x-settings.section>

<x-settings.section title="Credit &amp; Assessment" icon="grade">
    <div class="grid grid-cols-2 gap-md md:grid-cols-4">
        <x-settings.field label="Credits" name="credits">
            <x-settings.input type="number" step="0.5" min="0" name="credits" value="{{ old('credits', $s->credits ?? '') }}" placeholder="4"/>
        </x-settings.field>
        <x-settings.field label="Total Marks" name="total_marks">
            <x-settings.input type="number" min="0" name="total_marks" value="{{ old('total_marks', $s->total_marks ?? 100) }}"/>
        </x-settings.field>
        <x-settings.field label="Mid %" name="weight_mid">
            <x-settings.input type="number" min="0" max="100" name="weight_mid" value="{{ old('weight_mid', $s->weight_mid ?? 30) }}"/>
        </x-settings.field>
        <x-settings.field label="Final %" name="weight_final">
            <x-settings.input type="number" min="0" max="100" name="weight_final" value="{{ old('weight_final', $s->weight_final ?? 50) }}"/>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Curriculum &amp; Rules" icon="tune">
    <div class="space-y-md">
        <x-settings.field label="Curriculum Focus" name="curriculum_focus">
            <x-settings.textarea name="curriculum_focus" rows="2" placeholder="Outline key learning outcomes and syllabus scope...">{{ old('curriculum_focus', $s->curriculum_focus ?? '') }}</x-settings.textarea>
        </x-settings.field>
        <div class="grid grid-cols-1 gap-md md:grid-cols-2">
            <div class="rounded-lg border border-outline-variant p-4">
                <x-settings.toggle name="prerequisites_required" label="Prerequisites Required" :checked="old('prerequisites_required', $s->prerequisites_required ?? false)"/>
            </div>
            <div class="rounded-lg border border-outline-variant p-4">
                <x-settings.toggle name="lock_structural_changes" label="Lock Structural Changes" :checked="old('lock_structural_changes', $s->lock_structural_changes ?? false)"/>
            </div>
        </div>
    </div>
</x-settings.section>
