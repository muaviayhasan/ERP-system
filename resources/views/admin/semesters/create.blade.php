@extends('layouts.admin')

@section('title', 'Add Semester')

@section('content')
    <x-crud.form-page title="New Semester" subtitle="Define an academic semester."
        :back="route('semesters.index')" :action="route('semesters.store')" submit-label="Save Semester">
        @include('admin.semesters._form')
    </x-crud.form-page>
@endsection
