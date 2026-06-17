@extends('layouts.admin')

@section('title', 'Edit Subject')

@section('content')
    <x-crud.form-page title="Edit Subject" subtitle="Update {{ $subject->name }}."
        :back="route('subjects.index')" :action="route('subjects.update', $subject)" method="PUT" submit-label="Update Subject">
        @include('admin.subjects._form')
    </x-crud.form-page>
@endsection
