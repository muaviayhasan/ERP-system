@extends('layouts.admin')

@section('title', 'Mark Attendance')

@php
    use App\Http\Controllers\Admin\AttendanceController;
    $selectClass = 'w-full rounded-lg border border-outline-variant bg-white px-md py-2.5 text-body-md outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20';
    $segments = [
        'present' => ['P', 'peer-checked:bg-tertiary peer-checked:text-white peer-checked:border-tertiary'],
        'absent' => ['A', 'peer-checked:bg-error peer-checked:text-white peer-checked:border-error'],
        'late' => ['L', 'peer-checked:bg-orange-500 peer-checked:text-white peer-checked:border-orange-500'],
        'leave' => ['Lv', 'peer-checked:bg-secondary peer-checked:text-white peer-checked:border-secondary'],
    ];
@endphp

@section('content')
    <div class="mb-lg flex items-center gap-3">
        <a href="{{ route('attendances.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Mark Attendance</h2>
            <p class="text-body-md text-on-surface-variant">{{ format_date($context['date']) }}</p>
        </div>
    </div>

    {{-- 1. Context --}}
    <form method="GET" action="{{ route('attendances.create') }}" class="mb-lg rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
        <h3 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">1. Setup Context</h3>
        <div class="grid grid-cols-1 gap-md md:grid-cols-4">
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Section <span class="text-error">*</span></label>
                <select name="section_id" required class="{{ $selectClass }}">
                    <option value="">Select section...</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}" @selected($context['section_id'] === $section->id)>{{ $section->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Subject</label>
                <select name="subject_id" data-allow-clear placeholder="Optional" class="{{ $selectClass }}">
                    <option value="">Optional</option>
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected($context['subject_id'] === $subject->id)>{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Session</label>
                <select name="session" class="{{ $selectClass }}">
                    @foreach (AttendanceController::SESSIONS as $s)
                        <option value="{{ $s }}" @selected($context['session'] === $s)>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Date</label>
                <input type="date" name="date" value="{{ $context['date'] }}" class="{{ $selectClass }}"/>
            </div>
        </div>
        <div class="mt-md flex justify-end">
            <button type="submit" class="rounded-lg border border-outline-variant bg-surface-container-lowest px-lg py-2.5 font-bold text-on-surface-variant hover:bg-surface-container-low">
                <span class="material-symbols-outlined align-middle text-[18px]">groups</span> Load Roster
            </button>
        </div>
    </form>

    {{-- 2. Roster --}}
    @if ($context['section_id'] && $roster->isNotEmpty())
        <form method="POST" action="{{ route('attendances.store') }}"
              x-data="{ markAllPresent() { $root.querySelectorAll('input[value=present]').forEach(r => r.checked = true) } }">
            @csrf
            <input type="hidden" name="section_id" value="{{ $context['section_id'] }}"/>
            <input type="hidden" name="subject_id" value="{{ $context['subject_id'] }}"/>
            <input type="hidden" name="session" value="{{ $context['session'] }}"/>
            <input type="hidden" name="date" value="{{ $context['date'] }}"/>

            <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
                <div class="flex items-center justify-between border-b border-outline-variant bg-surface-container-low px-lg py-3">
                    <h3 class="text-label-md font-bold uppercase tracking-widest text-primary">2. Attendance List <span class="text-on-surface-variant">({{ $roster->count() }} students)</span></h3>
                    <button type="button" @click="markAllPresent()" class="text-label-md font-bold text-primary hover:underline">Mark All Present</button>
                </div>
                <div class="divide-y divide-outline-variant">
                    @foreach ($roster as $student)
                        @php
                            $name = $student->full_name ?: trim($student->first_name.' '.$student->last_name);
                            $initials = Str::of($name)->explode(' ')->take(2)->map(fn ($w) => Str::substr($w, 0, 1))->implode('');
                        @endphp
                        <div class="flex items-center justify-between px-lg py-3">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-container font-bold text-on-primary">{{ Str::upper($initials) ?: 'S' }}</div>
                                <div>
                                    <p class="font-bold text-on-surface">{{ $name }}</p>
                                    <p class="text-label-sm text-on-surface-variant">Roll No: {{ $student->roll_number ?? '—' }}</p>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                @foreach ($segments as $value => [$letter, $activeClass])
                                    <label class="cursor-pointer">
                                        <input type="radio" name="statuses[{{ $student->id }}]" value="{{ $value }}" class="peer sr-only" @checked($value === 'present')>
                                        <span class="flex h-8 w-9 items-center justify-center rounded-lg border border-outline-variant text-label-md font-bold text-on-surface-variant {{ $activeClass }}">{{ $letter }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-outline-variant bg-surface-container-low px-lg py-4">
                    <button type="submit" class="w-full rounded-lg bg-primary px-lg py-3 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                        Save Attendance
                    </button>
                </div>
            </div>
        </form>
    @elseif ($context['section_id'])
        <div class="rounded-xl border border-dashed border-outline-variant bg-surface-container-lowest p-12 text-center text-on-surface-variant">
            <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">person_off</span>
            No students are assigned to this section yet.
        </div>
    @else
        <div class="rounded-xl border border-dashed border-outline-variant bg-surface-container-lowest p-12 text-center text-on-surface-variant">
            <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">groups</span>
            Select a section and load the roster to begin marking attendance.
        </div>
    @endif
@endsection
