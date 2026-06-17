@extends('layouts.admin')

@section('title', 'Teacher Profile')

@php
    $name = $teacher->full_name ?: trim($teacher->first_name.' '.$teacher->last_name);
    $initials = Str::of($name)->explode(' ')->take(2)->map(fn ($w) => Str::substr($w, 0, 1))->implode('');
    $m = $teacher->metrics;
    $workloadPct = $teacher->max_workload_hours > 0 ? min(100, round(($teacher->weekly_workload_hours ?? 0) / $teacher->max_workload_hours * 100)) : 0;
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-center">
        <div class="flex items-center gap-3">
            <a href="{{ route('teachers.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">{{ $name }}</h2>
                <p class="text-body-md text-on-surface-variant">{{ $teacher->designation }} · {{ $teacher->teacher_code }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('teacher-assignments.create') }}"
               class="flex items-center gap-2 rounded-lg border border-outline-variant bg-surface-container-lowest px-lg py-2.5 text-label-md font-bold text-on-surface-variant hover:bg-surface-container-low">
                <span class="material-symbols-outlined text-[18px]">assignment_ind</span> Assign
            </a>
            @can('teachers.edit')
                <a href="{{ route('teachers.edit', $teacher) }}"
                   class="flex items-center gap-2 rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">edit</span> Edit Profile
                </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 gap-lg lg:grid-cols-3">
        {{-- Left card --}}
        <div class="space-y-lg">
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg text-center shadow-sm">
                @if ($teacher->photo_url)
                    <img src="{{ Storage::url($teacher->photo_url) }}" alt="{{ $name }}" class="mx-auto h-24 w-24 rounded-full object-cover"/>
                @else
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-primary-container text-headline-md font-bold text-on-primary">{{ Str::upper($initials) ?: 'T' }}</div>
                @endif
                <h3 class="mt-3 font-headline-md text-headline-md text-on-surface">{{ $name }}</h3>
                <p class="text-body-md text-on-surface-variant">{{ $teacher->designation }}</p>
                <span class="mt-2 inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $teacher->status === 'active' ? 'bg-tertiary/10 text-tertiary' : 'bg-outline-variant/40 text-on-surface-variant' }}">
                    {{ ucfirst($teacher->status ?? 'inactive') }}
                </span>
            </div>

            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <h4 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">Basic Information</h4>
                <dl class="space-y-3 text-body-md">
                    @foreach ([
                        ['Email', $teacher->email],
                        ['Phone', $teacher->phone],
                        ['CNIC', $teacher->cnic],
                        ['Campus', $teacher->campus?->name],
                        ['Department', $teacher->department?->name],
                        ['Joined', $teacher->joining_date ? format_date($teacher->joining_date) : null],
                    ] as [$label, $value])
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-label-sm text-on-surface-variant">{{ $label }}</dt>
                            <dd class="text-right font-medium text-on-surface">{{ $value ?: '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <h4 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">Operational Snapshot</h4>
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="rounded-lg border border-outline-variant p-3">
                        <p class="font-headline-md text-headline-md text-on-surface">{{ $m->classes_count ?? $teacher->assignments_count ?? 0 }}</p>
                        <p class="text-label-sm text-on-surface-variant">Classes</p>
                    </div>
                    <div class="rounded-lg border border-outline-variant p-3">
                        <p class="font-headline-md text-headline-md text-on-surface">{{ $m->subjects_count ?? 0 }}</p>
                        <p class="text-label-sm text-on-surface-variant">Subjects</p>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="mb-1 flex justify-between text-label-sm">
                        <span class="text-on-surface-variant">Weekly Workload</span>
                        <span class="font-bold text-primary">{{ $teacher->weekly_workload_hours ?? 0 }} / {{ $teacher->max_workload_hours ?? 40 }} hrs</span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-surface-container-high">
                        <div class="h-full bg-primary" style="width: {{ $workloadPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main --}}
        <div class="space-y-lg lg:col-span-2">
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <h4 class="mb-md font-headline-md text-headline-md text-on-surface">Departmental Summary</h4>
                <div class="grid grid-cols-2 gap-md sm:grid-cols-4">
                    @foreach ([
                        ['Rating', $m?->student_rating ? $m->student_rating.' / 5' : '—', 'star'],
                        ['Attendance', $m?->attendance_rate ? $m->attendance_rate.'%' : '—', 'event_available'],
                        ['Research', $m->research_papers ?? 0, 'science'],
                        ['Mentorship', $m->mentorship_count ?? 0, 'diversity_3'],
                    ] as [$label, $value, $icon])
                        <div class="rounded-lg border border-outline-variant p-md">
                            <div class="mb-1 flex items-center gap-2 text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
                                <span class="text-label-sm uppercase tracking-wider">{{ $label }}</span>
                            </div>
                            <p class="font-bold text-on-surface">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 gap-lg md:grid-cols-2">
                <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                    <h4 class="mb-md font-headline-md text-headline-md text-on-surface">Recent Activity</h4>
                    <div class="space-y-4">
                        @forelse ($teacher->activities as $activity)
                            <div class="flex gap-3">
                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                                    <span class="material-symbols-outlined text-[18px]">bolt</span>
                                </div>
                                <div>
                                    <p class="text-body-md font-medium text-on-surface">{{ $activity->title }}</p>
                                    <p class="text-label-sm text-on-surface-variant">{{ format_datetime($activity->occurred_at) }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-label-md text-on-surface-variant">No activity recorded yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                    <div class="mb-md flex items-center justify-between">
                        <h4 class="font-headline-md text-headline-md text-on-surface">Assigned Courses</h4>
                        <a href="{{ route('teacher-assignments.index', ['teacher' => $teacher->id]) }}" class="text-label-sm text-primary hover:underline">View all</a>
                    </div>
                    <div class="space-y-2">
                        @forelse ($teacher->assignments as $assignment)
                            <div class="flex items-center justify-between rounded-lg border border-outline-variant px-3 py-2">
                                <div>
                                    <p class="text-body-md font-medium text-on-surface">{{ $assignment->subject?->name ?? $assignment->course?->name ?? 'Unassigned subject' }}</p>
                                    <p class="text-label-sm text-on-surface-variant">{{ $assignment->program?->name ?? '—' }}</p>
                                </div>
                                <span class="rounded-full bg-secondary-container px-2 py-0.5 text-label-sm font-bold text-primary">{{ ucfirst($assignment->timetable_status) }}</span>
                            </div>
                        @empty
                            <p class="text-label-md text-on-surface-variant">No course assignments yet.
                                <a href="{{ route('teacher-assignments.create') }}" class="text-primary hover:underline">Create one</a>.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
