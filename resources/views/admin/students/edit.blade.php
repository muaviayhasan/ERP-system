@extends('layouts.admin')

@section('title', 'Edit Student')

@section('content')
    <div class="mb-lg flex items-center gap-3">
        <a href="{{ route('students.show', $student) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Edit Student</h2>
            <p class="text-body-md text-on-surface-variant">Update {{ $student->full_name ?: $student->first_name }}'s record.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('students.update', $student) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.students._form')
    </form>
@endsection
