@extends('layouts.admin')

@section('title', 'Edit Fee Structure')

@section('content')
    <x-crud.form-page title="Edit Fee Structure" subtitle="Update {{ $structure->name }}."
        :back="route('fee-structures.index')" :action="route('fee-structures.update', $structure)" method="PUT" submit-label="Update Structure">
        @include('admin.fee-structures._form')
    </x-crud.form-page>
@endsection
