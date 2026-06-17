@extends('layouts.admin')

@section('title', 'Edit Fee Category')

@section('content')
    <x-crud.form-page title="Edit Fee Category" subtitle="Update {{ $category->name }}."
        :back="route('fee-categories.index')" :action="route('fee-categories.update', $category)" method="PUT" submit-label="Update Category">
        @include('admin.fee-categories._form')
    </x-crud.form-page>
@endsection
