@extends('layouts.admin')

@section('title', 'Edit Schedule')

@section('content')
    <x-crud.form-page title="Edit Schedule" subtitle="Update the timetable details."
        :back="route('timetables.show', $timetable)" :action="route('timetables.update', $timetable)" method="PUT" submit-label="Update Schedule">
        @include('admin.timetables._form')
    </x-crud.form-page>
@endsection
