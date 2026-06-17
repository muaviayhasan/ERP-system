@extends('layouts.admin')

@section('title', 'Edit Batch')

@section('content')
    <x-crud.form-page title="Edit Batch" subtitle="Update {{ $batch->name }}."
        :back="route('batches.index')" :action="route('batches.update', $batch)" method="PUT" submit-label="Update Batch">
        @include('admin.batches._form')
    </x-crud.form-page>
@endsection
