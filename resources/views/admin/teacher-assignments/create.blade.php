@extends('layouts.admin')

@section('title', 'New Assignment')

@section('content')
    <x-crud.form-page title="New Teacher Assignment" subtitle="Assign a teacher to a subject or course."
        :back="route('teacher-assignments.index')" :action="route('teacher-assignments.store')" submit-label="Save Assignment">
        @include('admin.teacher-assignments._form')
    </x-crud.form-page>
@endsection
