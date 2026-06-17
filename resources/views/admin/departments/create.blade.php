@extends('layouts.admin')

@section('title', 'Add Department')

@section('content')
    <x-crud.form-page title="New Department" subtitle="Define an academic department."
        :back="route('departments.index')" :action="route('departments.store')" submit-label="Save Department">
        @include('admin.departments._form')
    </x-crud.form-page>
@endsection
