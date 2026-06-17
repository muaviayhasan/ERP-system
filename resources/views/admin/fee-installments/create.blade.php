@extends('layouts.admin')

@section('title', 'Add Installment')

@section('content')
    <x-crud.form-page title="New Installment" subtitle="Schedule an installment payment."
        :back="route('fee-installments.index')" :action="route('fee-installments.store')" submit-label="Save Installment">
        @include('admin.fee-installments._form')
    </x-crud.form-page>
@endsection
