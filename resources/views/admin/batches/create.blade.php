@extends('layouts.admin')

@section('title', 'Add Batch')

@section('content')
    <x-crud.form-page title="New Batch" subtitle="Configure batch settings and academic links."
        :back="route('batches.index')" :action="route('batches.store')" submit-label="Save Batch">
        @include('admin.batches._form')
    </x-crud.form-page>
@endsection
