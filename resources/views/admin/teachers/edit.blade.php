@extends('layouts.admin')

@section('title', 'Edit Teacher')

@section('content')
    <x-crud.form-page title="Edit Teacher" subtitle="Update {{ $teacher->full_name ?: $teacher->first_name }}'s record."
        :back="route('teachers.show', $teacher)" :action="route('teachers.update', $teacher)" method="PUT" enctype="multipart/form-data" submit-label="Update Teacher">
        @include('admin.teachers._form')
    </x-crud.form-page>
@endsection
