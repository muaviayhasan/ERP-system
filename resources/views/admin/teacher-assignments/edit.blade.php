@extends('layouts.admin')

@section('title', 'Edit Assignment')

@section('content')
    <x-crud.form-page title="Edit Assignment" subtitle="Update {{ $assignment->teacher?->full_name ?? 'this' }} assignment."
        :back="route('teacher-assignments.index')" :action="route('teacher-assignments.update', $assignment)" method="PUT" submit-label="Update Assignment">
        @include('admin.teacher-assignments._form')
    </x-crud.form-page>
@endsection
