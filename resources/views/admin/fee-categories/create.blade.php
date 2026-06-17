@extends('layouts.admin')

@section('title', 'Add Fee Category')

@section('content')
    <x-crud.form-page title="New Fee Category" subtitle="Define a reusable fee component."
        :back="route('fee-categories.index')" :action="route('fee-categories.store')" submit-label="Save Category">
        @include('admin.fee-categories._form')
    </x-crud.form-page>
@endsection
