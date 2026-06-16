@extends('layouts.admin')

@section('title', 'Add User')

@section('content')
    <div class="mb-lg flex items-center gap-3">
        <a href="{{ route('users.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Add User</h2>
            <p class="text-body-md text-on-surface-variant">Create a new system user and assign roles.</p>
        </div>
    </div>

    <div class="max-w-3xl rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            @include('admin.users._form')
        </form>
    </div>
@endsection
