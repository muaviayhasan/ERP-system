@extends('layouts.admin')

@section('title', 'User Management')

@php
    $statusStyles = [
        'active' => 'bg-tertiary/10 text-tertiary',
        'inactive' => 'bg-on-surface-variant/10 text-on-surface-variant',
        'suspended' => 'bg-error/10 text-error',
        'pending' => 'bg-primary/10 text-primary',
    ];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">User Management</h2>
            <p class="text-body-md text-on-surface-variant">Manage system users, their roles, and access status.</p>
        </div>
        @can('users.create')
            <a href="{{ route('users.create') }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">person_add</span> Add User
            </a>
        @endcan
    </div>

    {{-- Filters --}}
    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, or username..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="role" data-allow-clear placeholder="All Roles"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Roles</option>
            @foreach ($roles as $role)
                <option value="{{ $role }}" @selected(request('role') === $role)>{{ Str::headline($role) }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <select name="status" data-allow-clear placeholder="All Statuses"
                    class="w-full rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
                <option value="">All Statuses</option>
                @foreach (['active', 'inactive', 'suspended', 'pending'] as $st)
                    <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-4 text-on-surface-variant hover:bg-surface-container-low">
                <span class="material-symbols-outlined">filter_list</span>
            </button>
        </div>
    </form>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">User</th>
                        <th class="px-lg py-4 font-bold">Username</th>
                        <th class="px-lg py-4 font-bold">Roles</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 font-bold">Last Login</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($users as $user)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-container text-on-primary">
                                        <span class="material-symbols-outlined text-[20px]">person</span>
                                    </div>
                                    <div>
                                        <p class="font-bold text-on-surface">{{ $user->name }}</p>
                                        <p class="text-label-sm text-on-surface-variant">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $user->username ?? '—' }}</td>
                            <td class="px-lg py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($user->roles as $role)
                                        <span class="rounded-full bg-secondary-container px-2.5 py-0.5 text-label-sm font-medium text-primary">{{ Str::headline($role->name) }}</span>
                                    @empty
                                        <span class="text-label-sm text-outline">No roles</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-lg py-3">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $statusStyles[$user->status] ?? $statusStyles['inactive'] }}">
                                    {{ ucfirst($user->status ?? 'inactive') }}
                                </span>
                            </td>
                            <td class="px-lg py-3 text-label-md text-on-surface-variant">
                                <span @if ($user->last_login_at) title="{{ format_datetime($user->last_login_at) }}" @endif>
                                    {{ $user->last_login_at?->diffForHumans() ?? 'Never' }}
                                </span>
                            </td>
                            <td class="px-lg py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('users.edit')
                                        <a href="{{ route('users.edit', $user) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endcan
                                    @can('users.delete')
                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                                  onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="rounded-lg p-2 text-on-surface-variant hover:bg-error/10 hover:text-error" title="Delete">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-lg py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">group_off</span>
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
            {{ $users->links() }}
        </div>
    </div>
@endsection
