@extends('layouts.admin')

@section('title', 'Create Fee Structure')

@section('content')
    <x-crud.form-page title="Create Fee Structure" subtitle="Build a complete fee plan with line-item components."
        :back="route('fee-structures.index')" :action="route('fee-structures.store')" submit-label="Save Structure">
        @include('admin.fee-structures._form')
    </x-crud.form-page>
@endsection
