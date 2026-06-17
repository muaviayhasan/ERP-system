@extends('layouts.admin')

@section('title', 'Campus Management')

@php
    use App\Http\Controllers\Admin\CampusController;

    $statusStyles = [
        'active' => 'bg-tertiary/10 text-tertiary',
        'suspended' => 'bg-error-container text-error',
        'inactive' => 'bg-outline-variant/40 text-on-surface-variant',
    ];
    $statusDot = [
        'active' => 'bg-tertiary',
        'suspended' => 'bg-error',
        'inactive' => 'bg-on-surface-variant',
    ];
    // Cycle a few accent tints for the campus avatar based on its id.
    $avatarTones = [
        'bg-primary/10 text-primary',
        'bg-tertiary/10 text-tertiary',
        'bg-secondary-container text-on-secondary-container',
        'bg-error-container text-error',
    ];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Campus Management</h2>
            <p class="text-body-md text-on-surface-variant">Manage multiple campuses under your institute.</p>
        </div>
        @can('campuses.create')
            <a href="{{ route('campuses.create') }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> Add New Campus
            </a>
        @endcan
    </div>

    {{-- Stats --}}
    <div class="mb-lg grid grid-cols-1 gap-md md:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['Total Campuses', $stats['total'], 'domain', 'bg-primary/10 text-primary'],
            ['Active', $stats['active'], 'check_circle', 'bg-tertiary/10 text-tertiary'],
            ['Universities', $stats['universities'], 'school', 'bg-secondary-container text-on-secondary-container'],
            ['Admissions Open', $stats['admissions_open'], 'app_registration', 'bg-primary-fixed text-on-primary-fixed'],
        ] as [$label, $value, $icon, $tone])
            <div class="flex items-center justify-between rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <div>
                    <p class="mb-1 text-label-sm uppercase tracking-wider text-on-surface-variant">{{ $label }}</p>
                    <h3 class="font-headline-md text-headline-md text-on-surface">{{ number_format($value) }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $tone }}">
                    <span class="material-symbols-outlined">{{ $icon }}</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Filter by name or code..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="type" data-allow-clear placeholder="All Types"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Types</option>
            @foreach (CampusController::TYPES as $type)
                <option value="{{ $type }}" @selected(request('type') === $type)>{{ $type }}</option>
            @endforeach
        </select>
        <div class="flex gap-2">
            <select name="status" data-allow-clear placeholder="All Statuses"
                    class="w-full rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
                <option value="">All Statuses</option>
                @foreach (CampusController::STATUSES as $st)
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
                        <th class="px-lg py-4 font-bold">Campus</th>
                        <th class="px-lg py-4 font-bold">Code</th>
                        <th class="px-lg py-4 font-bold">Location</th>
                        <th class="px-lg py-4 font-bold">Type</th>
                        <th class="px-lg py-4 font-bold">Structure</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($campuses as $campus)
                        @php
                            $initials = Str::of($campus->name)->explode(' ')->take(2)->map(fn ($w) => Str::substr($w, 0, 1))->implode('');
                            $tone = $avatarTones[$campus->id % count($avatarTones)];
                            $status = $campus->status ?? 'inactive';
                        @endphp
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-4">
                                <div class="flex items-center gap-md">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg font-bold {{ $tone }}">{{ Str::upper($initials) ?: 'C' }}</div>
                                    <div>
                                        <div class="font-semibold text-on-surface">{{ $campus->name }}</div>
                                        @if ($campus->founded_year)
                                            <div class="text-label-sm text-on-surface-variant">Founded {{ $campus->founded_year }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-4 text-on-surface-variant">{{ $campus->code }}</td>
                            <td class="px-lg py-4 text-on-surface-variant">{{ collect([$campus->city, $campus->state_province])->filter()->implode(', ') ?: '—' }}</td>
                            <td class="px-lg py-4">
                                @if ($campus->institution_type)
                                    <span class="rounded-md bg-secondary-container px-2 py-1 text-label-sm text-on-secondary-container">{{ $campus->institution_type }}</span>
                                @else
                                    <span class="text-label-sm text-outline">—</span>
                                @endif
                            </td>
                            <td class="px-lg py-4">
                                <span class="text-body-md text-on-surface">{{ $campus->departments_count }}</span>
                                <span class="text-label-sm text-on-surface-variant">{{ Str::plural('Department', $campus->departments_count) }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-medium {{ $statusStyles[$status] ?? $statusStyles['inactive'] }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $statusDot[$status] ?? $statusDot['inactive'] }}"></span> {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="px-lg py-4">
                                <div class="flex items-center justify-end gap-1">
                                    @can('campuses.edit')
                                        <a href="{{ route('campuses.edit', $campus) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endcan
                                    @can('campuses.delete')
                                        <form method="POST" action="{{ route('campuses.destroy', $campus) }}"
                                              onsubmit="return confirm('Delete {{ $campus->name }}? This cannot be undone.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rounded-lg p-2 text-on-surface-variant hover:bg-error/10 hover:text-error" title="Delete">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-lg py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">domain_disabled</span>
                                No campuses found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
            {{ $campuses->links() }}
        </div>
    </div>
@endsection
