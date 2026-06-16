@extends('layouts.admin')

@section('title', 'Edit Role')

@section('content')
    <div class="mb-lg flex items-center gap-3">
        <a href="{{ route('roles.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Edit Role — {{ Str::headline($role->name) }}</h2>
            <p class="text-body-md text-on-surface-variant">Update the role name and its permissions.</p>
        </div>
    </div>

    <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
        <form method="POST" action="{{ route('roles.update', $role) }}">
            @csrf @method('PUT')
            @include('admin.roles._form')
        </form>
    </div>
@endsection
