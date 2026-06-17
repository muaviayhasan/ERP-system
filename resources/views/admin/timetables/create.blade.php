@extends('layouts.admin')

@section('title', 'New Schedule')

@section('content')
    <x-crud.form-page title="New Schedule" subtitle="Create a timetable, then add class slots."
        :back="route('timetables.index')" :action="route('timetables.store')" submit-label="Create Schedule">
        @include('admin.timetables._form')
    </x-crud.form-page>
@endsection
