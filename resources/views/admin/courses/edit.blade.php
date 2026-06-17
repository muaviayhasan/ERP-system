@extends('layouts.admin')

@section('title', 'Edit Course')

@section('content')
    <x-crud.form-page title="Edit Course" subtitle="Update {{ $course->name }}."
        :back="route('courses.index')" :action="route('courses.update', $course)" method="PUT" submit-label="Update Course">
        @include('admin.courses._form')
    </x-crud.form-page>
@endsection
