@extends('layouts.admin')

@section('title', 'Upload Document')

@section('content')
    <div class="mb-lg flex items-center gap-3">
        <a href="{{ route('student-documents.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Upload Document</h2>
            <p class="text-body-md text-on-surface-variant">Attach a record to a student's compliance file.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('student-documents.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.student-documents._form')
    </form>
@endsection
