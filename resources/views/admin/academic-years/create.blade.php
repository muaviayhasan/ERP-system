@extends('layouts.admin')

@section('title', 'Add Academic Year')

@section('content')
    <div class="mb-lg flex items-center gap-3">
        <a href="{{ route('academic-years.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">New Academic Year</h2>
            <p class="text-body-md text-on-surface-variant">Configure details and operational links.</p>
        </div>
    </div>

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('academic-years.store') }}">
            @csrf
            @include('admin.academic-years._form')
        </form>
    </div>
@endsection
