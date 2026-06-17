@extends('layouts.admin')

@section('title', 'Teacher Assignment')

@php
    use App\Http\Controllers\Admin\TeacherAssignmentController;
    $ttStyles = [
        'published' => 'bg-tertiary/10 text-tertiary',
        'scheduled' => 'bg-primary/10 text-primary',
        'pending' => 'bg-orange-100 text-orange-600',
    ];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Teacher Assignment</h2>
            <p class="text-body-md text-on-surface-variant">Assign teachers to classes, subjects, and academic structures.</p>
        </div>
        @can('teacher-assignments.create')
            <a href="{{ route('teacher-assignments.create') }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> New Assignment
            </a>
        @endcan
    </div>

    <div class="mb-lg grid grid-cols-1 gap-md md:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['Total Assignments', $stats['total'], 'assignment', 'bg-primary/10 text-primary'],
            ['Published', $stats['published'], 'event_available', 'bg-tertiary/10 text-tertiary'],
            ['Pending', $stats['pending'], 'hourglass_top', 'bg-orange-100 text-orange-600'],
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
        <select name="teacher" data-allow-clear placeholder="All Teachers"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Teachers</option>
            @foreach ($teachers as $teacher)
                <option value="{{ $teacher->id }}" @selected((int) request('teacher') === $teacher->id)>{{ $teacher->full_name }}</option>
            @endforeach
        </select>
        <select name="department" data-allow-clear placeholder="All Departments"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Departments</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @selected((int) request('department') === $department->id)>{{ $department->name }}</option>
            @endforeach
        </select>
        <select name="timetable_status" data-allow-clear placeholder="All Timetable Statuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Timetable Statuses</option>
            @foreach (TeacherAssignmentController::TIMETABLE_STATUSES as $ts)
                <option value="{{ $ts }}" @selected(request('timetable_status') === $ts)>{{ ucfirst($ts) }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Teacher</th>
                        <th class="px-lg py-4 font-bold">Department</th>
                        <th class="px-lg py-4 font-bold">Subject / Course</th>
                        <th class="px-lg py-4 font-bold">Weekly Hrs</th>
                        <th class="px-lg py-4 font-bold">Timetable</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($assignments as $assignment)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3">
                                <p class="font-bold text-on-surface">{{ $assignment->teacher?->full_name ?? '—' }}</p>
                                <p class="text-label-sm text-on-surface-variant">{{ $assignment->teacher?->teacher_code }}</p>
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $assignment->department?->name ?? '—' }}</td>
                            <td class="px-lg py-3">
                                <p class="text-on-surface">{{ $assignment->subject?->name ?? $assignment->course?->name ?? '—' }}</p>
                                <p class="text-label-sm text-on-surface-variant">{{ $assignment->program?->name }}</p>
                                @if ($assignment->has_conflict)
                                    <span class="mt-1 inline-flex items-center gap-0.5 text-label-sm text-error"><span class="material-symbols-outlined text-[14px]">warning</span> Conflict</span>
                                @endif
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $assignment->weekly_hours ?? '—' }}</td>
                            <td class="px-lg py-3">
                                <span class="rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $ttStyles[$assignment->timetable_status] ?? $ttStyles['pending'] }}">{{ ucfirst($assignment->timetable_status) }}</span>
                            </td>
                            <td class="px-lg py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('teacher-assignments.edit')
                                        <a href="{{ route('teacher-assignments.edit', $assignment) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endcan
                                    @can('teacher-assignments.delete')
                                        <form method="POST" action="{{ route('teacher-assignments.destroy', $assignment) }}" onsubmit="return confirm('Delete this assignment?');">
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
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">assignment_late</span>
                                No assignments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
            {{ $assignments->links() }}
        </div>
    </div>
@endsection
