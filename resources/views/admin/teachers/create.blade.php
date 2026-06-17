@extends('layouts.admin')

@section('title', 'Add Teacher')

@section('content')
    <x-crud.form-page title="Add Teacher" subtitle="Register a faculty member."
        :back="route('teachers.index')" :action="route('teachers.store')" enctype="multipart/form-data" submit-label="Save Teacher">
        @include('admin.teachers._form')
    </x-crud.form-page>
@endsection
