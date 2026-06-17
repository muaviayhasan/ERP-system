@php
    use App\Http\Controllers\Admin\SchoolClassController;
    $cl = $schoolClass ?? null;
    $fk = fn ($field, $model) => (int) old($field, $cl->{$field} ?? 0) === $model->id;
@endphp

<x-settings.section title="Basic Information" icon="groups">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Class Name" name="name" required>
            <x-settings.input name="name" maxlength="255" required value="{{ old('name', $cl->name ?? '') }}" placeholder="BSCS Semester 1"/>
        </x-settings.field>
        <x-settings.field label="Class Code" name="code" required>
            <x-settings.input name="code" maxlength="255" required value="{{ old('code', $cl->code ?? '') }}" placeholder="CS-S1"/>
        </x-settings.field>
        <x-settings.field label="Institution Type" name="institution_type">
            <x-settings.select name="institution_type" data-allow-clear placeholder="Select...">
                <option value="">Select...</option>
                @foreach (SchoolClassController::TYPES as $type)
                    <option value="{{ $type }}" @selected(old('institution_type', $cl->institution_type ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Academic Level" name="academic_level">
            <x-settings.select name="academic_level" data-allow-clear placeholder="Select...">
                <option value="">Select...</option>
                @foreach (SchoolClassController::LEVELS as $level)
                    <option value="{{ $level }}" @selected(old('academic_level', $cl->academic_level ?? '') === $level)>{{ $level }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Board" name="board">
            <x-settings.input name="board" maxlength="255" value="{{ old('board', $cl->board ?? '') }}" placeholder="e.g. Federal Board"/>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (SchoolClassController::STATUSES as $st)
                    <option value="{{ $st }}" @selected(old('status', $cl->status ?? 'active') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Description" name="description" class="md:col-span-2">
            <x-settings.textarea name="description" rows="2">{{ old('description', $cl->description ?? '') }}</x-settings.textarea>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Assignment &amp; Structure" icon="account_tree">
    <div class="grid grid-cols-1 gap-md md:grid-cols-3">
        <x-settings.field label="Campus" name="campus_id">
            <x-settings.select name="campus_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($campuses as $campus)
                    <option value="{{ $campus->id }}" @selected($fk('campus_id', $campus))>{{ $campus->name }}</option>
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
        <x-settings.field label="Coordinator" name="coordinator_user_id">
            <x-settings.select name="coordinator_user_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}" @selected($fk('coordinator_user_id', $user))>{{ $user->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Batch Count" name="batch_count">
            <x-settings.input type="number" min="0" name="batch_count" value="{{ old('batch_count', $cl->batch_count ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Total Credit Hours" name="total_credit_hours">
            <x-settings.input type="number" min="0" name="total_credit_hours" value="{{ old('total_credit_hours', $cl->total_credit_hours ?? '') }}"/>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Configuration" icon="tune">
    <div class="grid grid-cols-1 gap-md md:grid-cols-3">
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="multi_campus_sharing" label="Multi-campus Sharing" :checked="old('multi_campus_sharing', $cl->multi_campus_sharing ?? false)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="is_active" label="Active" :checked="old('is_active', $cl->is_active ?? true)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="allow_admissions" label="Allow Admissions" :checked="old('allow_admissions', $cl->allow_admissions ?? false)"/>
        </div>
    </div>
</x-settings.section>
