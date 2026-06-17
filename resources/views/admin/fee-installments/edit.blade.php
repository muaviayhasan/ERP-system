@extends('layouts.admin')

@section('title', 'Edit Installment')

@section('content')
    <x-crud.form-page title="Edit Installment" subtitle="Update installment #{{ $installment->installment_number }}."
        :back="route('fee-installments.index')" :action="route('fee-installments.update', $installment)" method="PUT" submit-label="Update Installment">
        @include('admin.fee-installments._form')
    </x-crud.form-page>
@endsection
