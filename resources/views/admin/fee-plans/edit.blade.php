@extends('layouts.admin')

@section('title', 'Edit Fee Plan')

@section('content')
    <x-crud.form-page title="Edit Fee Plan" subtitle="Update {{ $plan->name }}."
        :back="route('fee-plans.index')" :action="route('fee-plans.update', $plan)" method="PUT" submit-label="Update Plan">
        @include('admin.fee-plans._form')
    </x-crud.form-page>
@endsection
