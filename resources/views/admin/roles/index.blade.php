@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@php
    $roleIcons = [
        'super-admin' => 'shield_person', 'admin' => 'security', 'hod' => 'corporate_fare',
        'teacher' => 'school', 'accountant' => 'account_balance_wallet', 'librarian' => 'local_library',
        'transport-manager' => 'directions_bus', 'hostel-warden' => 'hotel', 'student' => 'badge', 'parent' => 'family_restroom',
    ];
    $systemRoles = ['super-admin', 'admin', 'hod', 'teacher', 'accountant', 'librarian', 'transport-manager', 'hostel-warden', 'student', 'parent'];
    $customCount = $roles->reject(fn ($r) => in_array($r->name, $systemRoles))->count();
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Roles &amp; Permissions</h2>
            <p class="text-body-md text-on-surface-variant">Define roles and control what each can access across the system.</p>
        </div>
        @can('roles.create')
            <a href="{{ route('roles.create') }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> Add Role
            </a>
        @endcan
    </div>

    {{-- KPIs --}}
    <div class="mb-lg grid grid-cols-2 gap-md md:grid-cols-4">
        @foreach ([
            ['Total Roles', $roles->count(), 'groups'],
            ['System Roles', $roles->count() - $customCount, 'verified_user'],
            ['Custom Roles', $customCount, 'tune'],
            ['Permissions', \Spatie\Permission\Models\Permission::count(), 'key'],
        ] as [$label, $value, $icon])
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-md shadow-sm">
                <div class="mb-1 flex items-center justify-between">
                    <span class="text-label-sm uppercase tracking-wider text-on-surface-variant">{{ $label }}</span>
                    <span class="material-symbols-outlined text-[20px] text-primary">{{ $icon }}</span>
                </div>
                <span class="font-display-lg text-display-lg text-on-surface">{{ $value }}</span>
            </div>
        @endforeach
    </div>

    {{-- Role cards --}}
    <div class="grid grid-cols-1 gap-md sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($roles as $role)
            <div class="group flex flex-col gap-4 rounded-xl border border-outline-variant bg-surface-container-lowest p-6 shadow-sm transition-all hover:border-primary/50">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                        <span class="material-symbols-outlined">{{ $roleIcons[$role->name] ?? 'badge' }}</span>
                    </div>
                    <div class="min-w-0">
                        <h4 class="truncate font-semibold text-on-surface">{{ Str::headline($role->name) }}</h4>
                        <p class="text-label-sm text-on-surface-variant">{{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if (in_array($role->name, $protected))
                        <span class="rounded bg-secondary-container px-2 py-1 text-[10px] font-bold uppercase text-primary">Protected</span>
                    @endif
                    <span class="rounded bg-surface-container-high px-2 py-1 text-[10px] font-bold uppercase text-on-surface-variant">
                        {{ $role->permissions_count }} permissions
                    </span>
                </div>

                <div class="mt-auto grid grid-cols-2 gap-2 border-t border-outline-variant/40 pt-4">
                    @can('roles.edit')
                        @unless (in_array($role->name, $protected))
                            <a href="{{ route('roles.edit', $role) }}" class="flex items-center justify-center gap-1 rounded-lg p-2 text-label-sm text-on-surface-variant transition-colors hover:bg-surface-container-low hover:text-primary">
                                <span class="material-symbols-outlined text-[18px]">edit</span> Edit
                            </a>
                        @else
                            <span class="flex items-center justify-center gap-1 rounded-lg p-2 text-label-sm text-outline">
                                <span class="material-symbols-outlined text-[18px]">lock</span> Locked
                            </span>
                        @endunless
                    @endcan
                    @can('roles.delete')
                        @unless (in_array($role->name, $protected))
                            <form method="POST" action="{{ route('roles.destroy', $role) }}"
                                  onsubmit="return confirm('Delete the {{ Str::headline($role->name) }} role?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="flex w-full items-center justify-center gap-1 rounded-lg p-2 text-label-sm text-on-surface-variant transition-colors hover:bg-error/10 hover:text-error">
                                    <span class="material-symbols-outlined text-[18px]">delete</span> Delete
                                </button>
                            </form>
                        @endunless
                    @endcan
                </div>
            </div>
        @endforeach
    </div>
@endsection
