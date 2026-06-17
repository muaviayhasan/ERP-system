@extends('layouts.admin')

@section('title', 'Assign Scholarship')

@section('content')
    <x-crud.form-page title="Assign Scholarship" subtitle="Grant a scholarship discount directly to a student."
        :back="route('scholarships.index')" :action="route('scholarship-assignments.store')" submit-label="Assign Scholarship">
        <x-settings.section title="Assignment" icon="person_add">
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="Student" name="student_id" required>
                    <x-settings.select name="student_id" data-allow-clear placeholder="Select a student..." required>
                        <option value="">Select a student...</option>
                        @foreach ($students as $student)<option value="{{ $student->id }}" @selected((int) old('student_id') === $student->id)>{{ $student->full_name }} ({{ $student->student_code }})</option>@endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Scholarship" name="scholarship_id" required>
                    <x-settings.select name="scholarship_id" data-allow-clear placeholder="Select a scholarship..." required>
                        <option value="">Select a scholarship...</option>
                        @foreach ($scholarships as $scholarship)<option value="{{ $scholarship->id }}" @selected((int) old('scholarship_id') === $scholarship->id)>{{ $scholarship->name }} ({{ $scholarship->value_type === 'percentage' ? $scholarship->value.'%' : format_money($scholarship->value) }})</option>@endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Discount Amount" name="discount_amount" hint="The resolved monetary discount for this student.">
                    <x-settings.input type="number" step="0.01" min="0" name="discount_amount" value="{{ old('discount_amount') }}"/>
                </x-settings.field>
                <x-settings.field label="Expires At" name="expires_at">
                    <x-settings.input type="date" name="expires_at" value="{{ old('expires_at') }}"/>
                </x-settings.field>
            </div>
        </x-settings.section>
    </x-crud.form-page>
@endsection
