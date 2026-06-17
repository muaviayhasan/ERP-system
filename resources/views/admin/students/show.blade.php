@extends('layouts.admin')

@section('title', 'Student Profile')

@php
    $name = $student->full_name ?: trim($student->first_name.' '.$student->last_name);
    $initials = Str::of($name)->explode(' ')->take(2)->map(fn ($w) => Str::substr($w, 0, 1))->implode('');
    $docStyles = [
        'verified' => 'bg-tertiary/10 text-tertiary',
        'pending' => 'bg-orange-100 text-orange-600',
        'rejected' => 'bg-error/10 text-error',
    ];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-center">
        <div class="flex items-center gap-3">
            <a href="{{ route('students.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">{{ $name }}</h2>
                <p class="text-body-md text-on-surface-variant">{{ $student->program?->name ?? 'Unassigned program' }} · {{ $student->student_code }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('student-documents.create', ['student' => $student->id]) }}"
               class="flex items-center gap-2 rounded-lg border border-outline-variant bg-surface-container-lowest px-lg py-2.5 text-label-md font-bold text-on-surface-variant hover:bg-surface-container-low">
                <span class="material-symbols-outlined text-[18px]">upload_file</span> Add Document
            </a>
            @can('students.edit')
                <a href="{{ route('students.edit', $student) }}"
                   class="flex items-center gap-2 rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">edit</span> Edit Profile
                </a>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-1 gap-lg lg:grid-cols-3">
        {{-- Left identity card --}}
        <div class="space-y-lg">
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg text-center shadow-sm">
                @if ($student->photo_url)
                    <img src="{{ Storage::url($student->photo_url) }}" alt="{{ $name }}" class="mx-auto h-24 w-24 rounded-full object-cover"/>
                @else
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-primary-container text-headline-md font-bold text-on-primary">{{ Str::upper($initials) ?: 'S' }}</div>
                @endif
                <h3 class="mt-3 font-headline-md text-headline-md text-on-surface">{{ $name }}</h3>
                <p class="text-body-md text-on-surface-variant">{{ $student->program?->name ?? '—' }}</p>
                <span class="mt-2 inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $student->status === 'active' ? 'bg-tertiary/10 text-tertiary' : 'bg-outline-variant/40 text-on-surface-variant' }}">
                    {{ ucfirst($student->status ?? 'inactive') }}
                </span>
            </div>

            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <h4 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">Personal Information</h4>
                <dl class="space-y-3 text-body-md">
                    @foreach ([
                        ['Father', $student->father_name],
                        ['Date of Birth', $student->date_of_birth ? format_date($student->date_of_birth) : null],
                        ['Gender', $student->gender ? ucfirst($student->gender) : null],
                        ['CNIC', $student->cnic],
                        ['Email', $student->email],
                        ['Phone', $student->phone],
                        ['Campus', $student->campus?->name],
                    ] as [$label, $value])
                        <div class="flex items-start justify-between gap-3">
                            <dt class="text-label-sm text-on-surface-variant">{{ $label }}</dt>
                            <dd class="text-right font-medium text-on-surface">{{ $value ?: '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <h4 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">Guardians</h4>
                @forelse ($student->guardians as $guardian)
                    <div class="flex items-center justify-between border-b border-outline-variant/50 py-2 last:border-0">
                        <div>
                            <p class="font-medium text-on-surface">{{ $guardian->full_name }}</p>
                            <p class="text-label-sm text-on-surface-variant">{{ ucfirst($guardian->pivot->relationship ?? $guardian->relationship ?? 'Guardian') }}</p>
                        </div>
                        <span class="text-label-sm text-on-surface-variant">{{ $guardian->phone }}</span>
                    </div>
                @empty
                    <p class="text-label-md text-on-surface-variant">No guardians linked yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Main column --}}
        <div class="space-y-lg lg:col-span-2">
            {{-- Current enrollment --}}
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <div class="mb-md flex items-center justify-between">
                    <h4 class="font-headline-md text-headline-md text-on-surface">Current Enrollment</h4>
                    @if ($student->academicYear)
                        <span class="rounded-full bg-secondary-container px-3 py-1 text-label-sm font-bold text-primary">{{ $student->academicYear->name }}</span>
                    @endif
                </div>
                <div class="grid grid-cols-1 gap-md sm:grid-cols-3">
                    @foreach ([
                        ['Program', $student->program?->name ?? '—', 'school'],
                        ['Advisor', $student->advisor?->name ?? 'Unassigned', 'support_agent'],
                        ['Semester', $student->currentSemester?->name ?? '—', 'date_range'],
                        ['Section', $student->section?->name ?? '—', 'grid_view'],
                        ['Batch', $student->batch?->name ?? '—', 'diversity_3'],
                        ['Credit Hours', $student->current_credit_hours ?? '—', 'tag'],
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
                {{-- Recent activity --}}
                <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                    <h4 class="mb-md font-headline-md text-headline-md text-on-surface">Recent Activity</h4>
                    <div class="space-y-4">
                        @forelse ($student->activities as $activity)
                            <div class="flex gap-3">
                                <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                                    <span class="material-symbols-outlined text-[18px]">bolt</span>
                                </div>
                                <div>
                                    <p class="text-body-md font-medium text-on-surface">{{ $activity->title }}</p>
                                    <p class="text-label-sm text-on-surface-variant">{{ format_date($activity->activity_date) }}@if ($activity->description) · {{ $activity->description }}@endif</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-label-md text-on-surface-variant">No activity recorded yet.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Documents --}}
                <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                    <div class="mb-md flex items-center justify-between">
                        <h4 class="font-headline-md text-headline-md text-on-surface">Documents</h4>
                        <a href="{{ route('student-documents.index', ['search' => $student->student_code]) }}" class="text-label-sm text-primary hover:underline">View all</a>
                    </div>
                    <div class="space-y-2">
                        @forelse ($student->documents as $doc)
                            <div class="flex items-center justify-between rounded-lg border border-outline-variant px-3 py-2">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-on-surface-variant">description</span>
                                    <div>
                                        <p class="text-body-md font-medium text-on-surface">{{ $doc->title }}</p>
                                        <p class="text-label-sm text-on-surface-variant">{{ $doc->document_type }}</p>
                                    </div>
                                </div>
                                <span class="rounded-full px-2 py-0.5 text-label-sm font-bold {{ $docStyles[$doc->status] ?? $docStyles['pending'] }}">{{ ucfirst($doc->status) }}</span>
                            </div>
                        @empty
                            <p class="text-label-md text-on-surface-variant">No documents uploaded.
                                <a href="{{ route('student-documents.create', ['student' => $student->id]) }}" class="text-primary hover:underline">Upload one</a>.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
