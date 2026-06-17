@extends('layouts.admin')

@section('title', 'Add Campus')

@section('content')
    <div class="mb-lg flex items-center gap-3">
        <a href="{{ route('campuses.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Add New Campus</h2>
            <p class="text-body-md text-on-surface-variant">Register a new branch or academic unit.</p>
        </div>
    </div>

    <div class="max-w-4xl">
        <form method="POST" action="{{ route('campuses.store') }}">
            @csrf
            @include('admin.campuses._form')
        </form>
    </div>
@endsection
