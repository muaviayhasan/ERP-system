@extends('layouts.admin')

@section('title', 'Edit Scholarship Policy')

@section('content')
    <x-crud.form-page title="Edit Scholarship Policy" subtitle="Update {{ $scholarship->name }}."
        :back="route('scholarships.index')" :action="route('scholarships.update', $scholarship)" method="PUT" submit-label="Update Policy">
        @include('admin.scholarships._form')
    </x-crud.form-page>
@endsection
