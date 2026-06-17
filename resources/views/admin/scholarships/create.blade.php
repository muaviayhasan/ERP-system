@extends('layouts.admin')

@section('title', 'Create Scholarship Policy')

@section('content')
    <x-crud.form-page title="Create Scholarship Policy" subtitle="Define a scholarship or financial-aid scheme."
        :back="route('scholarships.index')" :action="route('scholarships.store')" submit-label="Save Policy">
        @include('admin.scholarships._form')
    </x-crud.form-page>
@endsection
