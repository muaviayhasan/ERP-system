@extends('layouts.admin')

@section('title', 'Edit Semester')

@section('content')
    <x-crud.form-page title="Edit Semester" subtitle="Update {{ $semester->name }}."
        :back="route('semesters.index')" :action="route('semesters.update', $semester)" method="PUT" submit-label="Update Semester">
        @include('admin.semesters._form')
    </x-crud.form-page>
@endsection
