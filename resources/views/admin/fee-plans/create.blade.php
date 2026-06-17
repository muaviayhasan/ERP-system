@extends('layouts.admin')

@section('title', 'Add Fee Plan')

@section('content')
    <x-crud.form-page title="New Fee Plan" subtitle="Define a payment schedule."
        :back="route('fee-plans.index')" :action="route('fee-plans.store')" submit-label="Save Plan">
        @include('admin.fee-plans._form')
    </x-crud.form-page>
@endsection
