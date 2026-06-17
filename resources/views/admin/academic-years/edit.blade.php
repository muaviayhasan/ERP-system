@extends('layouts.admin')

@section('title', 'Edit Academic Year')

@section('content')
    <div class="mb-lg flex items-center gap-3">
        <a href="{{ route('academic-years.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Edit Academic Year</h2>
            <p class="text-body-md text-on-surface-variant">Update the {{ $academicYear->name }} cycle.</p>
        </div>
    </div>

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('academic-years.update', $academicYear) }}">
            @csrf @method('PUT')
            @include('admin.academic-years._form')
        </form>
    </div>
@endsection
