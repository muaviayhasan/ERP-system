@extends('layouts.admin')

@section('title', 'Edit Guardian')

@section('content')
    <x-crud.form-page title="Edit Guardian" subtitle="Update {{ $guardian->full_name }}'s details and student links."
        :back="route('guardians.index')" :action="route('guardians.update', $guardian)" method="PUT" submit-label="Update Guardian">
        @include('admin.guardians._form')
    </x-crud.form-page>
@endsection
