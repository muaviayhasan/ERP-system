@extends('layouts.admin')

@section('title', 'Add Subject')

@section('content')
    <x-crud.form-page title="New Subject" subtitle="Set up academic parameters for the subject."
        :back="route('subjects.index')" :action="route('subjects.store')" submit-label="Save Subject">
        @include('admin.subjects._form')
    </x-crud.form-page>
@endsection
