@extends('layouts.admin')

@section('title', 'Add Program')

@section('content')
    <x-crud.form-page title="New Academic Program" subtitle="Define a degree program and its structure."
        :back="route('programs.index')" :action="route('programs.store')" submit-label="Save Program">
        @include('admin.programs._form')
    </x-crud.form-page>
@endsection
