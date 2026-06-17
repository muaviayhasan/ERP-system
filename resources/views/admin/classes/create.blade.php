@extends('layouts.admin')

@section('title', 'Add Class')

@section('content')
    <x-crud.form-page title="New Class Configuration" subtitle="Define an academic class."
        :back="route('classes.index')" :action="route('classes.store')" submit-label="Save Class">
        @include('admin.classes._form')
    </x-crud.form-page>
@endsection
