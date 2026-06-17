@extends('layouts.admin')

@section('title', 'Add Guardian')

@section('content')
    <x-crud.form-page title="Add New Guardian" subtitle="Register a new guardian and link to students."
        :back="route('guardians.index')" :action="route('guardians.store')" submit-label="Save Guardian">
        @include('admin.guardians._form')
    </x-crud.form-page>
@endsection
