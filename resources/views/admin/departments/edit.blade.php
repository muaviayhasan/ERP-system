@extends('layouts.admin')

@section('title', 'Edit Department')

@section('content')
    <x-crud.form-page title="Edit Department" subtitle="Update {{ $department->name }}."
        :back="route('departments.index')" :action="route('departments.update', $department)" method="PUT" submit-label="Update Department">
        @include('admin.departments._form')
    </x-crud.form-page>
@endsection
