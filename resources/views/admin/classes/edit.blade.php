@extends('layouts.admin')

@section('title', 'Edit Class')

@section('content')
    <x-crud.form-page title="Edit Class" subtitle="Update {{ $schoolClass->name }}."
        :back="route('classes.index')" :action="route('classes.update', $schoolClass)" method="PUT" submit-label="Update Class">
        @include('admin.classes._form')
    </x-crud.form-page>
@endsection
