@extends('layouts.admin')

@section('title', 'Timetable')

@php
    use App\Http\Controllers\Admin\TimetableSlotController;
    $inputClass = 'w-full rounded-lg border border-outline-variant bg-white px-md py-2.5 text-body-md outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20';
    $slotTones = [
        'lecture' => 'border-primary bg-primary/5',
        'lab' => 'border-tertiary bg-tertiary/5',
        'tutorial' => 'border-orange-400 bg-orange-50',
        'seminar' => 'border-secondary bg-secondary-container/30',
    ];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-center">
        <div class="flex items-center gap-3">
            <a href="{{ route('timetables.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface">{{ $timetable->name ?: 'Schedule #'.$timetable->id }}</h2>
                <p class="text-body-md text-on-surface-variant">
                    {{ collect([$timetable->program?->name, $timetable->semester?->name, $timetable->campus?->name])->filter()->join(' · ') ?: 'Unscoped' }}
                </p>
            </div>
        </div>
        @can('timetables.edit')
            <a href="{{ route('timetables.edit', $timetable) }}"
               class="flex items-center gap-2 rounded-lg border border-outline-variant bg-surface-container-lowest px-lg py-2.5 text-label-md font-bold text-on-surface-variant hover:bg-surface-container-low">
                <span class="material-symbols-outlined text-[18px]">edit</span> Edit Details
            </a>
        @endcan
    </div>

    {{-- Add slot --}}
    @can('timetables.create')
        <div x-data="{ open: false }" class="mb-lg rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
            <button @click="open = !open" class="flex w-full items-center justify-between px-lg py-4 text-left">
                <span class="flex items-center gap-2 font-headline-md text-headline-md text-on-surface">
                    <span class="material-symbols-outlined text-primary">add_circle</span> New Class Slot
                </span>
                <span class="material-symbols-outlined transition-transform" :class="open && 'rotate-180'">expand_more</span>
            </button>
            <div x-show="open" x-collapse x-cloak class="border-t border-outline-variant p-lg">
                <form method="POST" action="{{ route('timetable-slots.store', $timetable) }}">
                    @csrf
                    <div class="grid grid-cols-1 gap-md md:grid-cols-3">
                        <div class="space-y-1">
                            <label class="text-label-sm font-bold text-on-surface-variant">Day <span class="text-error">*</span></label>
                            <select name="day_of_week" required class="{{ $inputClass }}">
                                @foreach ($days as $day)
                                    <option value="{{ $day }}" @selected(old('day_of_week') === $day)>{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-label-sm font-bold text-on-surface-variant">Subject</label>
                            <select name="subject_id" data-allow-clear placeholder="Select..." class="{{ $inputClass }}">
                                <option value="">Unassigned</option>
                                @foreach ($subjects as $subject)<option value="{{ $subject->id }}">{{ $subject->name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-label-sm font-bold text-on-surface-variant">Teacher</label>
                            <select name="teacher_id" data-allow-clear placeholder="Select..." class="{{ $inputClass }}">
                                <option value="">Unassigned</option>
                                @foreach ($teachers as $teacher)<option value="{{ $teacher->id }}">{{ $teacher->full_name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-label-sm font-bold text-on-surface-variant">Section</label>
                            <select name="section_id" data-allow-clear placeholder="Select..." class="{{ $inputClass }}">
                                <option value="">Unassigned</option>
                                @foreach ($sections as $section)<option value="{{ $section->id }}">{{ $section->name }}</option>@endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-label-sm font-bold text-on-surface-variant">Start Time <span class="text-error">*</span></label>
                            <input type="time" name="start_time" required value="{{ old('start_time') }}" class="{{ $inputClass }}"/>
                        </div>
                        <div class="space-y-1">
                            <label class="text-label-sm font-bold text-on-surface-variant">End Time</label>
                            <input type="time" name="end_time" value="{{ old('end_time') }}" class="{{ $inputClass }}"/>
                        </div>
                        <div class="space-y-1">
                            <label class="text-label-sm font-bold text-on-surface-variant">Room</label>
                            <input type="text" name="room" maxlength="255" value="{{ old('room') }}" placeholder="Room 302" class="{{ $inputClass }}"/>
                        </div>
                        <div class="space-y-1">
                            <label class="text-label-sm font-bold text-on-surface-variant">Capacity</label>
                            <input type="number" name="capacity" min="0" value="{{ old('capacity') }}" placeholder="45" class="{{ $inputClass }}"/>
                        </div>
                        <div class="space-y-1">
                            <label class="text-label-sm font-bold text-on-surface-variant">Type</label>
                            <select name="slot_type" class="{{ $inputClass }}">
                                @foreach (TimetableSlotController::SLOT_TYPES as $type)
                                    <option value="{{ $type }}" @selected(old('slot_type') === $type)>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-md flex justify-end">
                        <button type="submit" class="rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">Save Slot</button>
                    </div>
                </form>
            </div>
        </div>
    @endcan

    {{-- Weekly grid --}}
    <div class="grid grid-cols-1 gap-md sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        @foreach ($days as $day)
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
                <div class="border-b border-outline-variant bg-surface-container-low px-3 py-2 text-center text-label-md font-bold uppercase tracking-wider text-on-surface-variant">{{ Str::substr($day, 0, 3) }}</div>
                <div class="space-y-2 p-2">
                    @forelse ($slotsByDay[$day] ?? [] as $slot)
                        <div class="rounded-lg border-l-4 p-2 {{ $slotTones[$slot->slot_type] ?? $slotTones['lecture'] }}">
                            <p class="text-label-md font-bold text-on-surface">{{ $slot->subject?->name ?? ucfirst($slot->slot_type) }}</p>
                            <p class="text-label-sm text-on-surface-variant">{{ $slot->start_time?->format('H:i') }}@if ($slot->end_time)–{{ $slot->end_time->format('H:i') }}@endif</p>
                            @if ($slot->teacher)<p class="text-label-sm text-on-surface-variant">{{ $slot->teacher->full_name }}</p>@endif
                            @if ($slot->room)<p class="text-label-sm text-on-surface-variant">📍 {{ $slot->room }}</p>@endif
                            @if ($slot->has_conflict)<p class="text-label-sm font-bold text-error">⚠ Conflict</p>@endif
                            <div class="mt-1 flex gap-1">
                                @can('timetables.edit')
                                    <a href="{{ route('timetable-slots.edit', $slot) }}" class="rounded p-1 text-on-surface-variant hover:text-primary" title="Edit"><span class="material-symbols-outlined text-[16px]">edit</span></a>
                                @endcan
                                @can('timetables.delete')
                                    <form method="POST" action="{{ route('timetable-slots.destroy', $slot) }}" onsubmit="return confirm('Remove this slot?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded p-1 text-on-surface-variant hover:text-error" title="Remove"><span class="material-symbols-outlined text-[16px]">delete</span></button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <p class="py-4 text-center text-label-sm text-outline">No classes</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
@endsection
