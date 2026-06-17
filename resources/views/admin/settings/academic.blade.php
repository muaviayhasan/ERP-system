@extends('layouts.admin')

@section('title', 'Academic Settings')

@php
    $gradingSystems = ['GPA (4.0 Scale)', 'CGPA (10.0 Scale)', 'Percentage', 'Letter Grade'];
    $examStructures = ['Semester Structure', 'Annual Structure', 'Term Structure'];
@endphp

@section('content')
    <x-settings.page
        title="Academic Settings"
        subtitle="Grading, exam weightage, attendance thresholds, and promotion rules."
        :action="route('settings.academic.update')">

        {{-- Grading & Passing --}}
        <x-settings.section title="Grading & Passing" icon="school">
            <div class="grid grid-cols-1 gap-md md:grid-cols-3">
                <x-settings.field label="Grading System" name="grading_system" required>
                    <x-settings.select name="grading_system">
                        @foreach ($gradingSystems as $opt)
                            <option value="{{ $opt }}" @selected(old('grading_system', $a->grading_system ?? 'GPA (4.0 Scale)') === $opt)>{{ $opt }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Pass Mark Threshold (%)" name="pass_mark_threshold" required>
                    <x-settings.input type="number" name="pass_mark_threshold" min="0" max="100"
                        value="{{ old('pass_mark_threshold', $a->pass_mark_threshold ?? 40) }}"/>
                </x-settings.field>
                <x-settings.field label="Exam Structure" name="exam_structure" required>
                    <x-settings.select name="exam_structure">
                        @foreach ($examStructures as $opt)
                            <option value="{{ $opt }}" @selected(old('exam_structure', $a->exam_structure ?? 'Semester Structure') === $opt)>{{ $opt }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- Exam Weightage --}}
        <x-settings.section title="Exam Weightage" icon="balance" desc="Components should total 100%.">
            <div class="grid grid-cols-2 gap-md md:grid-cols-4">
                @foreach ([
                    'weight_final' => ['Final', 50],
                    'weight_midterm' => ['Midterm', 30],
                    'weight_assignments_lab' => ['Assignments / Lab', 10],
                    'weight_quizzes' => ['Quizzes', 10],
                ] as $key => [$label, $def])
                    <x-settings.field label="{{ $label }} (%)" name="{{ $key }}" required>
                        <x-settings.input type="number" name="{{ $key }}" min="0" max="100"
                            value="{{ old($key, $a->$key ?? $def) }}"/>
                    </x-settings.field>
                @endforeach
            </div>
        </x-settings.section>

        {{-- Attendance --}}
        <x-settings.section title="Attendance Rules" icon="fact_check">
            <div class="grid grid-cols-1 gap-md md:grid-cols-3">
                <x-settings.field label="Min. Attendance Required (%)" name="min_attendance_required" required>
                    <x-settings.input type="number" name="min_attendance_required" min="0" max="100"
                        value="{{ old('min_attendance_required', $a->min_attendance_required ?? 75) }}"/>
                </x-settings.field>
                <x-settings.field label="Grace Minutes" name="attendance_grace_minutes" required>
                    <x-settings.input type="number" name="attendance_grace_minutes" min="0" max="240"
                        value="{{ old('attendance_grace_minutes', $a->attendance_grace_minutes ?? 15) }}"/>
                </x-settings.field>
                <x-settings.field label="Daily Session Limit" name="attendance_session_limit"
                    hint="Optional, e.g. 8.">
                    <x-settings.input name="attendance_session_limit" maxlength="50"
                        value="{{ old('attendance_session_limit', $a->attendance_session_limit) }}"/>
                </x-settings.field>
                <x-settings.field label="Warning Threshold (%)" name="attendance_warning_threshold" required>
                    <x-settings.input type="number" name="attendance_warning_threshold" min="0" max="100"
                        value="{{ old('attendance_warning_threshold', $a->attendance_warning_threshold ?? 75) }}"/>
                </x-settings.field>
                <x-settings.field label="Critical Threshold (%)" name="attendance_critical_threshold" required>
                    <x-settings.input type="number" name="attendance_critical_threshold" min="0" max="100"
                        value="{{ old('attendance_critical_threshold', $a->attendance_critical_threshold ?? 60) }}"/>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- Promotion --}}
        <x-settings.section title="Promotion Rules" icon="trending_up">
            <div class="mb-md rounded-lg border border-outline-variant p-4">
                <x-settings.toggle name="promotion_enabled" label="Enable Student Promotion"
                    desc="Allow promoting students to the next class/semester."
                    :checked="old('promotion_enabled', $a->promotion_enabled ?? true)"/>
            </div>
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="Minimum GPA for Promotion" name="promotion_min_gpa" hint="Leave blank to ignore.">
                    <x-settings.input type="number" step="0.01" min="0" max="10" name="promotion_min_gpa"
                        value="{{ old('promotion_min_gpa', $a->promotion_min_gpa) }}"/>
                </x-settings.field>
                <x-settings.field label="Max Failed Subjects Allowed" name="promotion_max_fail_subjects" hint="Leave blank to ignore.">
                    <x-settings.input type="number" min="0" max="20" name="promotion_max_fail_subjects"
                        value="{{ old('promotion_max_fail_subjects', $a->promotion_max_fail_subjects) }}"/>
                </x-settings.field>
            </div>
        </x-settings.section>

        {{-- University Mode --}}
        <x-settings.section title="University Mode" icon="account_balance">
            <div class="mb-md rounded-lg border border-outline-variant p-4">
                <x-settings.toggle name="university_mode_enabled" label="Enable University (Credit-Hours) Mode"
                    desc="Switch from class-based to credit-hour based academics."
                    :checked="old('university_mode_enabled', $a->university_mode_enabled ?? false)"/>
            </div>
            <div class="grid grid-cols-1 gap-md md:grid-cols-4">
                <x-settings.field label="Min Credit Load" name="min_credit_load">
                    <x-settings.input type="number" min="0" max="60" name="min_credit_load"
                        value="{{ old('min_credit_load', $a->min_credit_load) }}"/>
                </x-settings.field>
                <x-settings.field label="Max Credit Load" name="max_credit_load">
                    <x-settings.input type="number" min="0" max="60" name="max_credit_load"
                        value="{{ old('max_credit_load', $a->max_credit_load) }}"/>
                </x-settings.field>
                <x-settings.field label="Year Start Month" name="year_start_month">
                    <x-settings.input name="year_start_month" maxlength="20"
                        value="{{ old('year_start_month', $a->year_start_month) }}" placeholder="September"/>
                </x-settings.field>
                <x-settings.field label="Makeup Class Allowance" name="makeup_class_allowance">
                    <x-settings.input name="makeup_class_allowance" maxlength="50"
                        value="{{ old('makeup_class_allowance', $a->makeup_class_allowance) }}"/>
                </x-settings.field>
            </div>
        </x-settings.section>
    </x-settings.page>
@endsection
