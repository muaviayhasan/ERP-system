@extends('layouts.admin')

@section('title', 'Edit Section')

@section('content')
    <x-crud.form-page title="Edit Section" subtitle="Update {{ $section->name }}."
        :back="route('sections.index')" :action="route('sections.update', $section)" method="PUT" submit-label="Update Section">
        @include('admin.sections._form')
    </x-crud.form-page>
@endsection
