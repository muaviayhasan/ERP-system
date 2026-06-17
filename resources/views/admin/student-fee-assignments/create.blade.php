@extends('layouts.admin')

@section('title', 'Assign Fee Plan')

@section('content')
    <x-crud.form-page title="Assign Fee Plan" subtitle="Configure billing for an individual student."
        :back="route('student-fee-assignments.index')" :action="route('student-fee-assignments.store')" submit-label="Assign Fee Plan">
        @include('admin.student-fee-assignments._form')
    </x-crud.form-page>
@endsection
