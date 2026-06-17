@extends('layouts.admin')

@section('title', 'Course Management')

@php use App\Http\Controllers\Admin\CourseController; @endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Course Management</h2>
            <p class="text-body-md text-on-surface-variant">Manage academic courses and curriculum structure.</p>
        </div>
        @can('courses.create')
            <a href="{{ route('courses.create') }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> Add Course
            </a>
        @endcan
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search courses or codes..."
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
            @foreach (CourseController::STATUSES as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Course</th>
                        <th class="px-lg py-4 font-bold">Type</th>
                        <th class="px-lg py-4 font-bold">Program</th>
                        <th class="px-lg py-4 font-bold">Semester</th>
                        <th class="px-lg py-4 font-bold">Credits</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($courses as $course)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-4">
                                <div class="font-semibold text-on-surface">{{ $course->name }}</div>
                                <div class="text-label-sm text-on-surface-variant">{{ $course->code }}</div>
                            </td>
                            <td class="px-lg py-4">
                                @if ($course->type)
                                    <span class="rounded-md bg-secondary-container px-2 py-1 text-label-sm text-on-secondary-container">{{ $course->type }}</span>
                                @else <span class="text-outline">—</span> @endif
                            </td>
                            <td class="px-lg py-4 text-on-surface-variant">{{ $course->program?->name ?? '—' }}</td>
                            <td class="px-lg py-4 text-on-surface-variant">{{ $course->semester?->name ?? '—' }}</td>
                            <td class="px-lg py-4 text-on-surface-variant">{{ $course->credit_hours ?? '—' }}</td>
                            <td class="px-lg py-4">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-medium {{ ($course->status ?? 'active') === 'active' ? 'bg-tertiary/10 text-tertiary' : 'bg-outline-variant/40 text-on-surface-variant' }}">
                                    {{ ucfirst($course->status ?? 'active') }}
                                </span>
                            </td>
                            <td class="px-lg py-4">
                                <div class="flex items-center justify-end gap-1">
                                    @can('courses.edit')
                                        <a href="{{ route('courses.edit', $course) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endcan
                                    @can('courses.delete')
                                        <form method="POST" action="{{ route('courses.destroy', $course) }}" onsubmit="return confirm('Delete {{ $course->name }}?');">
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
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">menu_book</span>
                                No courses found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
            {{ $courses->links() }}
        </div>
    </div>
@endsection
