@extends('layouts.admin')

@section('title', 'Academic Year Management')

@php
    $statusStyles = [
        'active' => 'bg-tertiary/10 text-tertiary',
        'upcoming' => 'bg-primary/10 text-primary',
        'completed' => 'bg-outline-variant/30 text-on-surface-variant',
    ];
    $current = request('status');
    $tabs = [
        '' => 'All',
        'active' => 'Active',
        'upcoming' => 'Upcoming',
        'completed' => 'Completed',
    ];
    // Operational-link icons shown per cycle (dimmed when not configured).
    $opLinks = [
        ['flag' => 'fees_configured', 'icon' => 'payments', 'title' => 'Fees Configured'],
        ['flag' => 'exams_configured', 'icon' => 'description', 'title' => 'Exams Configured'],
        ['flag' => 'attendance_enabled', 'icon' => 'person_check', 'title' => 'Attendance Enabled'],
    ];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md lg:flex-row lg:items-end">
        <div>
            <nav class="mb-2 flex items-center gap-2 text-label-sm text-on-surface-variant">
                <span>Academics</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="font-semibold text-primary">Year Management</span>
            </nav>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Academic Year Management</h2>
            <p class="text-body-md text-on-surface-variant">Define and manage academic cycles for institute operations.</p>
        </div>
        <div class="flex items-center gap-md">
            <div class="flex gap-1 rounded-lg border border-outline-variant bg-surface-container-low p-1">
                @foreach ($tabs as $value => $label)
                    <a href="{{ route('academic-years.index', array_filter(['status' => $value])) }}"
                       class="rounded-md px-4 py-1.5 text-label-md transition-colors {{ (string) $current === (string) $value ? 'bg-white font-bold text-primary shadow-sm' : 'text-on-surface-variant hover:text-on-surface' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
            @can('academic-years.create')
                <a href="{{ route('academic-years.create') }}"
                   class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">add</span> Add Academic Year
                </a>
            @endcan
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-md uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-semibold">Academic Year</th>
                        <th class="px-lg py-4 font-semibold">Duration</th>
                        <th class="px-lg py-4 font-semibold">Status</th>
                        <th class="px-lg py-4 font-semibold">Scope</th>
                        <th class="px-lg py-4 font-semibold">Operational Links</th>
                        <th class="px-lg py-4 text-right font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($years as $year)
                        @php
                            $status = $year->status ?? 'upcoming';
                            $icon = ['active' => 'calendar_today', 'upcoming' => 'event_repeat', 'completed' => 'history'][$status] ?? 'calendar_today';
                        @endphp
                        <tr class="transition-colors hover:bg-surface-container-low/50">
                            <td class="px-lg py-5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $status === 'completed' ? 'bg-surface-container-high text-on-surface-variant' : 'bg-primary/10 text-primary' }}">
                                        <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
                                    </div>
                                    <span class="font-bold text-on-surface">{{ $year->name }}</span>
                                </div>
                            </td>
                            <td class="px-lg py-5 text-on-surface-variant">{{ format_date($year->start_date) }} &ndash; {{ format_date($year->end_date) }}</td>
                            <td class="px-lg py-5">
                                <span class="rounded-full px-3 py-1 text-[11px] font-bold uppercase {{ $statusStyles[$status] ?? $statusStyles['upcoming'] }}">{{ $status }}</span>
                            </td>
                            <td class="px-lg py-5 text-on-surface-variant">
                                @if ($year->scope === 'specific_campuses')
                                    {{ $year->campuses->pluck('name')->join(', ') ?: 'Specific Campuses' }}
                                @else
                                    All Campuses
                                @endif
                            </td>
                            <td class="px-lg py-5">
                                <div class="flex gap-3">
                                    @foreach ($opLinks as $link)
                                        <span class="material-symbols-outlined text-[20px] {{ $year->{$link['flag']} ? 'text-primary' : 'text-outline/50' }}"
                                              title="{{ $link['title'] }}">{{ $link['icon'] }}</span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-lg py-5">
                                <div class="flex items-center justify-end gap-2">
                                    @can('academic-years.edit')
                                        @if ($status === 'upcoming')
                                            <form method="POST" action="{{ route('academic-years.activate', $year) }}">
                                                @csrf
                                                <button type="submit" class="rounded-full bg-primary px-3 py-1 text-[11px] font-bold uppercase text-on-primary hover:opacity-90">Activate</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('academic-years.edit', $year) }}" class="rounded-lg p-1.5 text-on-surface-variant hover:bg-surface-container-high hover:text-primary" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endcan
                                    @can('academic-years.delete')
                                        <form method="POST" action="{{ route('academic-years.destroy', $year) }}"
                                              onsubmit="return confirm('Delete the {{ $year->name }} cycle? This cannot be undone.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rounded-lg p-1.5 text-on-surface-variant hover:bg-error/10 hover:text-error" title="Delete">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-lg py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">calendar_month</span>
                                No academic years found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-lowest px-lg py-4">
            {{ $years->links() }}
        </div>
    </div>

    {{-- Readiness / automation bento --}}
    <div class="mt-lg grid grid-cols-1 gap-lg lg:grid-cols-3">
        <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm lg:col-span-2">
            <h3 class="mb-lg font-headline-md text-headline-md text-on-surface">Cycle Overview</h3>
            <div class="grid grid-cols-3 gap-md">
                @foreach ([['Active', $counts['active'], 'text-tertiary'], ['Upcoming', $counts['upcoming'], 'text-primary'], ['Completed', $counts['completed'], 'text-on-surface-variant']] as [$label, $value, $tone])
                    <div class="rounded-lg border border-outline-variant p-md">
                        <p class="text-label-sm uppercase tracking-wider text-on-surface-variant">{{ $label }}</p>
                        <p class="mt-1 font-headline-md text-headline-md {{ $tone }}">{{ $value }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="relative flex flex-col justify-between overflow-hidden rounded-xl bg-primary-container p-lg text-on-primary-container shadow-sm">
            <div class="absolute -bottom-4 -right-4 opacity-10">
                <span class="material-symbols-outlined text-[120px]">calendar_month</span>
            </div>
            <div class="relative">
                <h3 class="mb-2 font-headline-md text-headline-md font-extrabold">Automate Cycles</h3>
                <p class="text-body-md opacity-90">Enable auto-activation of upcoming years and graceful archival of historical data.</p>
            </div>
        </div>
    </div>
@endsection
