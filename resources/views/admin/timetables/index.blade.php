@extends('layouts.admin')

@section('title', 'Timetable Management')

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Timetable Management</h2>
            <p class="text-body-md text-on-surface-variant">Create and manage academic schedules and class timetables.</p>
        </div>
        @can('timetables.create')
            <a href="{{ route('timetables.create') }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> New Schedule
            </a>
        @endcan
    </div>

    <div class="mb-lg grid grid-cols-1 gap-md md:grid-cols-3">
        @foreach ([
            ['Schedules', $stats['total'], 'calendar_month', 'bg-primary/10 text-primary'],
            ['Scheduled Classes', $stats['slots'], 'event', 'bg-tertiary/10 text-tertiary'],
            ['Conflicts', $stats['conflicts'], 'warning', 'bg-error/10 text-error'],
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

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-3">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search schedules..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="program" data-allow-clear placeholder="All Programs"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Programs</option>
            @foreach ($programs as $program)
                <option value="{{ $program->id }}" @selected((int) request('program') === $program->id)>{{ $program->name }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Schedule</th>
                        <th class="px-lg py-4 font-bold">Program</th>
                        <th class="px-lg py-4 font-bold">Semester</th>
                        <th class="px-lg py-4 font-bold">Week</th>
                        <th class="px-lg py-4 font-bold">Classes</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($timetables as $timetable)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3">
                                <a href="{{ route('timetables.show', $timetable) }}" class="font-bold text-on-surface hover:text-primary">{{ $timetable->name ?: 'Schedule #'.$timetable->id }}</a>
                                <p class="text-label-sm text-on-surface-variant">{{ $timetable->campus?->name }}</p>
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $timetable->program?->name ?? '—' }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $timetable->semester?->name ?? '—' }}</td>
                            <td class="px-lg py-3 text-label-md text-on-surface-variant">
                                @if ($timetable->week_start_date) {{ format_date($timetable->week_start_date) }} – {{ format_date($timetable->week_end_date) }} @else — @endif
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $timetable->slots_count }}</td>
                            <td class="px-lg py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('timetables.show', $timetable) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Open grid">
                                        <span class="material-symbols-outlined text-[20px]">calendar_view_week</span>
                                    </a>
                                    @can('timetables.edit')
                                        <a href="{{ route('timetables.edit', $timetable) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endcan
                                    @can('timetables.delete')
                                        <form method="POST" action="{{ route('timetables.destroy', $timetable) }}" onsubmit="return confirm('Delete this schedule and its slots?');">
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
                            <td colspan="6" class="px-lg py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">calendar_month</span>
                                No timetables yet. <a href="{{ route('timetables.create') }}" class="text-primary hover:underline">Create one</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
            {{ $timetables->links() }}
        </div>
    </div>
@endsection
