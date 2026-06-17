@extends('layouts.admin')

@section('title', 'Add Staff')

@section('content')
    <x-crud.form-page title="Add New Staff" subtitle="Register an administrative or support staff member."
        :back="route('staff.index')" :action="route('staff.store')" enctype="multipart/form-data" submit-label="Save Staff">
        @include('admin.staff._form')
    </x-crud.form-page>
@endsection
