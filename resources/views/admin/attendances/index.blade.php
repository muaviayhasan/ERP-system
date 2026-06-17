@extends('layouts.admin')

@section('title', 'Attendance Management')

@php
    use App\Http\Controllers\Admin\AttendanceController;
    $statusStyles = [
        'present' => 'bg-tertiary/10 text-tertiary',
        'absent' => 'bg-error/10 text-error',
        'late' => 'bg-orange-100 text-orange-600',
        'leave' => 'bg-secondary-container text-on-secondary-container',
    ];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Attendance Management</h2>
            <p class="text-body-md text-on-surface-variant">Track and manage student attendance records.</p>
        </div>
        @can('attendances.create')
            <a href="{{ route('attendances.create', ['date' => $date]) }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">how_to_reg</span> Mark Attendance
            </a>
        @endcan
    </div>

    <div class="mb-lg grid grid-cols-2 gap-md lg:grid-cols-4">
        @foreach ([
            ['Present', $stats['present'], 'check_circle', 'bg-tertiary/10 text-tertiary'],
            ['Absent', $stats['absent'], 'cancel', 'bg-error/10 text-error'],
            ['Late', $stats['late'], 'schedule', 'bg-orange-100 text-orange-600'],
            ['On Leave', $stats['leave'], 'event_busy', 'bg-secondary-container text-on-secondary-container'],
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

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="space-y-1">
            <input type="date" name="date" value="{{ $date }}"
                   class="w-full rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary"/>
        </div>
        <select name="section" data-allow-clear placeholder="All Sections"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Sections</option>
            @foreach ($sections as $section)
                <option value="{{ $section->id }}" @selected((int) request('section') === $section->id)>{{ $section->name }}</option>
            @endforeach
        </select>
        <select name="status" data-allow-clear placeholder="All Statuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Statuses</option>
            @foreach (AttendanceController::STATUSES as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-4 text-on-surface-variant hover:bg-surface-container-low">
            <span class="material-symbols-outlined align-middle">filter_list</span> Apply
        </button>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Student</th>
                        <th class="px-lg py-4 font-bold">Section</th>
                        <th class="px-lg py-4 font-bold">Subject</th>
                        <th class="px-lg py-4 font-bold">Session</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($records as $record)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3">
                                <p class="font-bold text-on-surface">{{ $record->student?->full_name ?? '—' }}</p>
                                <p class="text-label-sm text-on-surface-variant">{{ $record->student?->student_code }}</p>
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $record->section?->name ?? '—' }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $record->subject?->name ?? '—' }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ ucfirst($record->session ?? '—') }}</td>
                            <td class="px-lg py-3">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $statusStyles[$record->status] ?? '' }}">{{ ucfirst($record->status) }}</span>
                            </td>
                            <td class="px-lg py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('attendances.delete')
                                        <form method="POST" action="{{ route('attendances.destroy', $record) }}" onsubmit="return confirm('Remove this record?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rounded-lg p-2 text-on-surface-variant hover:bg-error/10 hover:text-error" title="Remove">
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
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">event_busy</span>
                                No attendance records for {{ format_date($date) }}.
                                @can('attendances.create')<a href="{{ route('attendances.create', ['date' => $date]) }}" class="text-primary hover:underline">Mark now</a>.@endcan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
            {{ $records->links() }}
        </div>
    </div>
@endsection
