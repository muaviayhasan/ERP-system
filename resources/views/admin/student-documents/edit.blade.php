@extends('layouts.admin')

@section('title', 'Verify Document')

@section('content')
    <div class="mb-lg flex items-center gap-3">
        <a href="{{ route('student-documents.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Document Details</h2>
            <p class="text-body-md text-on-surface-variant">Review and verify {{ $document->student?->full_name }}'s document.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('student-documents.update', $document) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.student-documents._form')
    </form>
@endsection
