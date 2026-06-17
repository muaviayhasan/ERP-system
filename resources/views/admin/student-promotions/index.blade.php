@extends('layouts.admin')

@section('title', 'Student Promotion')

@php use App\Http\Controllers\Admin\StudentController; @endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Student Promotion</h2>
            <p class="text-body-md text-on-surface-variant">Promote students to the next class, semester, or academic year.</p>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search students..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="program" data-allow-clear placeholder="All Programs"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Programs</option>
            @foreach ($programs as $program)
                <option value="{{ $program->id }}" @selected((int) request('program') === $program->id)>{{ $program->name }}</option>
            @endforeach
        </select>
        <select name="status" data-allow-clear placeholder="All Statuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Statuses</option>
            @foreach (StudentController::STATUSES as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
    </form>

    @can('students.edit')
        <form method="POST" action="{{ route('student-promotions.promote') }}" x-data="{ selected: [] }"
              onsubmit="return confirm('Promote the selected students?');">
            @csrf

            {{-- Target panel --}}
            <div class="mb-lg rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <h3 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">Promotion Target</h3>
                <div class="grid grid-cols-1 gap-md md:grid-cols-4">
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">To Academic Year</label>
                        <select name="to_academic_year_id" data-allow-clear placeholder="Keep current"
                                class="w-full rounded-lg border border-outline-variant bg-white px-md py-2.5 text-body-md outline-none focus:border-primary">
                            <option value="">Keep current</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">To Program</label>
                        <select name="to_program_id" data-allow-clear placeholder="Keep current"
                                class="w-full rounded-lg border border-outline-variant bg-white px-md py-2.5 text-body-md outline-none focus:border-primary">
                            <option value="">Keep current</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">To Semester</label>
                        <select name="to_semester_id" data-allow-clear placeholder="Keep current"
                                class="w-full rounded-lg border border-outline-variant bg-white px-md py-2.5 text-body-md outline-none focus:border-primary">
                            <option value="">Keep current</option>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">To Section</label>
                        <select name="to_section_id" data-allow-clear placeholder="Keep current"
                                class="w-full rounded-lg border border-outline-variant bg-white px-md py-2.5 text-body-md outline-none focus:border-primary">
                            <option value="">Keep current</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-md flex items-center justify-between border-t border-outline-variant pt-md">
                    <p class="text-label-md text-on-surface-variant"><span x-text="selected.length">0</span> selected</p>
                    <button type="submit" :disabled="selected.length === 0"
                            class="flex items-center gap-2 rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95 disabled:opacity-40">
                        <span class="material-symbols-outlined text-[18px]">rocket_launch</span> Start Promotion Process
                    </button>
                </div>
            </div>

            {{-- Queue --}}
            <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead class="border-b border-outline-variant bg-surface-container-low">
                            <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                                <th class="px-lg py-4">
                                    <input type="checkbox" title="Select all"
                                           @change="
                                               const checks = $root.querySelectorAll('[data-row-check]');
                                               checks.forEach(c => c.checked = $event.target.checked);
                                               selected = $event.target.checked ? Array.from(checks).map(c => c.value) : [];
                                           "/>
                                </th>
                                <th class="px-lg py-4 font-bold">Student</th>
                                <th class="px-lg py-4 font-bold">Program</th>
                                <th class="px-lg py-4 font-bold">Section</th>
                                <th class="px-lg py-4 font-bold">Status</th>
                                <th class="px-lg py-4 font-bold">Eligibility</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @forelse ($students as $student)
                                @php
                                    $name = $student->full_name ?: trim($student->first_name.' '.$student->last_name);
                                    $eligible = $student->status === 'active';
                                @endphp
                                <tr class="transition-colors hover:bg-surface-container-low/40">
                                    <td class="px-lg py-3">
                                        <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" data-row-check
                                               @change="$event.target.checked ? selected.push('{{ $student->id }}') : selected.splice(selected.indexOf('{{ $student->id }}'), 1)"/>
                                    </td>
                                    <td class="px-lg py-3">
                                        <p class="font-bold text-on-surface">{{ $name }}</p>
                                        <p class="text-label-sm text-on-surface-variant">{{ $student->student_code }} · {{ $student->currentSemester?->name ?? 'No semester' }}</p>
                                    </td>
                                    <td class="px-lg py-3 text-on-surface-variant">{{ $student->program?->name ?? '—' }}</td>
                                    <td class="px-lg py-3 text-on-surface-variant">{{ $student->section?->name ?? '—' }}</td>
                                    <td class="px-lg py-3">
                                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $student->status === 'active' ? 'bg-tertiary/10 text-tertiary' : 'bg-outline-variant/40 text-on-surface-variant' }}">{{ ucfirst($student->status ?? 'inactive') }}</span>
                                    </td>
                                    <td class="px-lg py-3">
                                        @if ($eligible)
                                            <span class="rounded-full bg-tertiary/10 px-2.5 py-0.5 text-label-sm font-bold uppercase text-tertiary">Eligible</span>
                                        @else
                                            <span class="rounded-full bg-outline-variant/40 px-2.5 py-0.5 text-label-sm font-bold uppercase text-on-surface-variant">Not Eligible</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-lg py-12 text-center text-on-surface-variant">
                                        <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">trending_up</span>
                                        No students match the current filters.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
                    {{ $students->links() }}
                </div>
            </div>
        </form>
    @else
        <p class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg text-on-surface-variant">You don't have permission to promote students.</p>
    @endcan
@endsection
