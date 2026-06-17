@extends('layouts.admin')

@section('title', 'Add Section')

@section('content')
    <x-crud.form-page title="New Section" subtitle="Configure section properties."
        :back="route('sections.index')" :action="route('sections.store')" submit-label="Save Section">
        @include('admin.sections._form')
    </x-crud.form-page>
@endsection
