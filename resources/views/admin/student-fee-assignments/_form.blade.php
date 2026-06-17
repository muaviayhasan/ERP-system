@php
    use App\Http\Controllers\Admin\StudentFeeAssignmentController;
    $a = $assignment ?? null;
    $fk = fn ($field, $model) => (int) old($field, $a->{$field} ?? 0) === $model->id;
    $due = old('next_due_date', isset($a) ? $a->next_due_date?->format('Y-m-d') : '');
@endphp

<x-settings.section title="Selection" icon="badge">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Student" name="student_id" required>
            <x-settings.select name="student_id" data-allow-clear placeholder="Select a student..." required>
                <option value="">Select a student...</option>
                @foreach ($students as $student)<option value="{{ $student->id }}" @selected($fk('student_id', $student))>{{ $student->full_name }} ({{ $student->student_code }})</option>@endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Fee Structure" name="fee_structure_id">
            <x-settings.select name="fee_structure_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($structures as $structure)<option value="{{ $structure->id }}" @selected($fk('fee_structure_id', $structure))>{{ $structure->name }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Fee Plan" name="fee_plan_id">
            <x-settings.select name="fee_plan_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($plans as $plan)<option value="{{ $plan->id }}" @selected($fk('fee_plan_id', $plan))>{{ $plan->name }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Program" name="program_id">
            <x-settings.select name="program_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($programs as $program)<option value="{{ $program->id }}" @selected($fk('program_id', $program))>{{ $program->name }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Semester" name="semester_id">
            <x-settings.select name="semester_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($semesters as $semester)<option value="{{ $semester->id }}" @selected($fk('semester_id', $semester))>{{ $semester->name }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Academic Year" name="academic_year_id">
            <x-settings.select name="academic_year_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($academicYears as $year)<option value="{{ $year->id }}" @selected($fk('academic_year_id', $year))>{{ $year->name }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Billing" icon="request_quote" desc="Final payable is fee minus scholarship; pending is computed from paid.">
    <div class="grid grid-cols-1 gap-md md:grid-cols-3">
        <x-settings.field label="Total Fee" name="total_fee">
            <x-settings.input type="number" step="0.01" min="0" name="total_fee" value="{{ old('total_fee', $a->total_fee ?? 0) }}"/>
        </x-settings.field>
        <x-settings.field label="Scholarship Amount" name="scholarship_amount">
            <x-settings.input type="number" step="0.01" min="0" name="scholarship_amount" value="{{ old('scholarship_amount', $a->scholarship_amount ?? 0) }}"/>
        </x-settings.field>
        <x-settings.field label="Already Paid" name="total_paid">
            <x-settings.input type="number" step="0.01" min="0" name="total_paid" value="{{ old('total_paid', $a->total_paid ?? 0) }}"/>
        </x-settings.field>
        <x-settings.field label="Next Due Date" name="next_due_date">
            <x-settings.input type="date" name="next_due_date" value="{{ $due }}"/>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (StudentFeeAssignmentController::STATUSES as $st)<option value="{{ $st }}" @selected(old('status', $a->status ?? 'pending') === $st)>{{ ucfirst($st) }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
    </div>
    <div class="mt-md grid grid-cols-1 gap-md md:grid-cols-2">
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="late_fee_enabled" label="Late Fee Enabled" :checked="old('late_fee_enabled', $a->late_fee_enabled ?? true)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="email_notifications_enabled" label="Email Notifications" :checked="old('email_notifications_enabled', $a->email_notifications_enabled ?? true)"/>
        </div>
    </div>
</x-settings.section>
