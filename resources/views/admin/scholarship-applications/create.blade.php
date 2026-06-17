@extends('layouts.admin')

@section('title', 'New Scholarship Application')

@php use App\Http\Controllers\Admin\ScholarshipApplicationController; @endphp

@section('content')
    <x-crud.form-page title="New Scholarship Application" subtitle="Submit a student's request for financial aid."
        :back="route('scholarship-applications.index')" :action="route('scholarship-applications.store')" submit-label="Submit Application">

        <x-settings.section title="Applicant" icon="badge">
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="Student" name="student_id" required>
                    <x-settings.select name="student_id" data-allow-clear placeholder="Select a student..." required>
                        <option value="">Select a student...</option>
                        @foreach ($students as $student)<option value="{{ $student->id }}" @selected((int) old('student_id') === $student->id)>{{ $student->full_name }} ({{ $student->student_code }})</option>@endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Scholarship" name="scholarship_id">
                    <x-settings.select name="scholarship_id" data-allow-clear placeholder="Select...">
                        <option value="">Unassigned</option>
                        @foreach ($scholarships as $scholarship)<option value="{{ $scholarship->id }}" @selected((int) old('scholarship_id') === $scholarship->id)>{{ $scholarship->name }}</option>@endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Program" name="program_id">
                    <x-settings.select name="program_id" data-allow-clear placeholder="Select...">
                        <option value="">Unassigned</option>
                        @foreach ($programs as $program)<option value="{{ $program->id }}" @selected((int) old('program_id') === $program->id)>{{ $program->name }}</option>@endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Semester" name="semester_id">
                    <x-settings.select name="semester_id" data-allow-clear placeholder="Select...">
                        <option value="">Unassigned</option>
                        @foreach ($semesters as $semester)<option value="{{ $semester->id }}" @selected((int) old('semester_id') === $semester->id)>{{ $semester->name }}</option>@endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Type" name="type" required>
                    <x-settings.input name="type" maxlength="255" required value="{{ old('type', 'Need Based') }}" placeholder="Need Based"/>
                </x-settings.field>
                <x-settings.field label="Priority" name="priority">
                    <x-settings.select name="priority">
                        @foreach (ScholarshipApplicationController::PRIORITIES as $p)<option value="{{ $p }}" @selected(old('priority', 'normal') === $p)>{{ ucfirst($p) }}</option>@endforeach
                    </x-settings.select>
                </x-settings.field>
            </div>
        </x-settings.section>

        <x-settings.section title="Request &amp; Eligibility" icon="request_quote">
            <div class="grid grid-cols-1 gap-md md:grid-cols-3">
                <x-settings.field label="Original Fee" name="original_fee">
                    <x-settings.input type="number" step="0.01" min="0" name="original_fee" value="{{ old('original_fee') }}"/>
                </x-settings.field>
                <x-settings.field label="Requested Discount %" name="requested_discount_percent">
                    <x-settings.input type="number" step="0.01" min="0" max="100" name="requested_discount_percent" value="{{ old('requested_discount_percent') }}"/>
                </x-settings.field>
                <x-settings.field label="Requested Value" name="requested_value">
                    <x-settings.input type="number" step="0.01" min="0" name="requested_value" value="{{ old('requested_value') }}"/>
                </x-settings.field>
                <x-settings.field label="CGPA" name="cgpa">
                    <x-settings.input type="number" step="0.01" min="0" max="4" name="cgpa" value="{{ old('cgpa') }}"/>
                </x-settings.field>
                <x-settings.field label="Documents Count" name="documents_count">
                    <x-settings.input type="number" min="0" name="documents_count" value="{{ old('documents_count', 0) }}"/>
                </x-settings.field>
                <x-settings.field label="Application Date" name="application_date" required>
                    <x-settings.input type="date" name="application_date" required value="{{ old('application_date', now()->format('Y-m-d')) }}"/>
                </x-settings.field>
                <x-settings.field label="Reason" name="reason" class="md:col-span-3">
                    <x-settings.textarea name="reason" rows="3" placeholder="Explain the basis for this request...">{{ old('reason') }}</x-settings.textarea>
                </x-settings.field>
            </div>
            <div class="mt-md grid grid-cols-1 gap-md md:grid-cols-3">
                <div class="rounded-lg border border-outline-variant p-4"><x-settings.toggle name="gpa_check_passed" label="GPA Requirement Met" :checked="old('gpa_check_passed', false)"/></div>
                <div class="rounded-lg border border-outline-variant p-4"><x-settings.toggle name="policy_compliance_passed" label="Policy Compliance OK" :checked="old('policy_compliance_passed', false)"/></div>
                <div class="rounded-lg border border-outline-variant p-4"><x-settings.toggle name="no_duplicate_passed" label="No Duplicate Aid" :checked="old('no_duplicate_passed', false)"/></div>
            </div>
        </x-settings.section>
    </x-crud.form-page>
@endsection
