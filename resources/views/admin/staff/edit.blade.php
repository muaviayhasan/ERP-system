@extends('layouts.admin')

@section('title', 'Edit Staff')

@section('content')
    <x-crud.form-page title="Edit Staff" subtitle="Update {{ $staff->full_name ?: $staff->first_name }}'s record."
        :back="route('staff.index')" :action="route('staff.update', $staff)" method="PUT" enctype="multipart/form-data" submit-label="Update Staff">
        @include('admin.staff._form')
    </x-crud.form-page>
@endsection
