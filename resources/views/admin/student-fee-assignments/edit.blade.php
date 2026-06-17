@extends('layouts.admin')

@section('title', 'Edit Fee Assignment')

@section('content')
    <x-crud.form-page title="Edit Fee Assignment" subtitle="Update {{ $assignment->student?->full_name }}'s billing."
        :back="route('student-fee-assignments.index')" :action="route('student-fee-assignments.update', $assignment)" method="PUT" submit-label="Update Assignment">
        @include('admin.student-fee-assignments._form')
    </x-crud.form-page>
@endsection
