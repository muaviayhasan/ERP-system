@extends('layouts.admin')

@section('title', 'Edit Program')

@section('content')
    <x-crud.form-page title="Edit Program" subtitle="Update {{ $program->name }}."
        :back="route('programs.index')" :action="route('programs.update', $program)" method="PUT" submit-label="Update Program">
        @include('admin.programs._form')
    </x-crud.form-page>
@endsection
