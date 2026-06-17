@extends('layouts.admin')

@section('title', 'New Admission')

@section('content')
    <div class="mb-lg flex items-center gap-3">
        <a href="{{ route('students.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Student Admission</h2>
            <p class="text-body-md text-on-surface-variant">Register and enroll a new student into the system.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.students._form')
    </form>
@endsection
