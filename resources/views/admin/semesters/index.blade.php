@extends('layouts.admin')

@section('title', 'Semester Management')

@php
    use App\Http\Controllers\Admin\SemesterController;
    $statusStyles = [
        'active' => 'bg-tertiary/10 text-tertiary',
        'upcoming' => 'bg-primary/10 text-primary',
        'completed' => 'bg-outline-variant/40 text-on-surface-variant',
    ];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Semester Management</h2>
            <p class="text-body-md text-on-surface-variant">Manage academic semesters within programs and departments.</p>
        </div>
        @can('semesters.create')
            <a href="{{ route('semesters.create') }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> Add Semester
            </a>
        @endcan
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search semesters or codes..."
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
            @foreach (SemesterController::STATUSES as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Semester</th>
                        <th class="px-lg py-4 font-bold">Program / Dept</th>
                        <th class="px-lg py-4 font-bold">Timeline</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($semesters as $semester)
                        @php $status = $semester->status ?? 'upcoming'; @endphp
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-4">
                                <div class="font-semibold text-on-surface">{{ $semester->name }}</div>
                                <div class="text-label-sm text-on-surface-variant">{{ $semester->code }}</div>
                            </td>
                            <td class="px-lg py-4 text-on-surface-variant">
                                {{ $semester->program?->name ?? '—' }}
                                @if ($semester->department)<div class="text-label-sm">{{ $semester->department->name }}</div>@endif
                            </td>
                            <td class="px-lg py-4 text-label-md text-on-surface-variant">
                                @if ($semester->start_date) {{ format_date($semester->start_date) }} – {{ format_date($semester->end_date) }} @else — @endif
                            </td>
                            <td class="px-lg py-4">
                                <span class="rounded-full px-2.5 py-0.5 text-label-sm font-medium {{ $statusStyles[$status] ?? $statusStyles['upcoming'] }}">{{ ucfirst($status) }}</span>
                            </td>
                            <td class="px-lg py-4">
                                <div class="flex items-center justify-end gap-1">
                                    @can('semesters.edit')
                                        <a href="{{ route('semesters.edit', $semester) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endcan
                                    @can('semesters.delete')
                                        <form method="POST" action="{{ route('semesters.destroy', $semester) }}" onsubmit="return confirm('Delete {{ $semester->name }}?');">
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
                            <td colspan="5" class="px-lg py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">date_range</span>
                                No semesters found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
            {{ $semesters->links() }}
        </div>
    </div>
@endsection
