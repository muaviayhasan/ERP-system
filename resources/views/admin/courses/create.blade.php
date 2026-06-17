@extends('layouts.admin')

@section('title', 'Add Course')

@section('content')
    <x-crud.form-page title="New Course" subtitle="Set up a course and its assessment structure."
        :back="route('courses.index')" :action="route('courses.store')" submit-label="Save Course">
        @include('admin.courses._form')
    </x-crud.form-page>
@endsection
