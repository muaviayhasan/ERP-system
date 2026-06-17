@extends('layouts.admin')

@section('title', 'Program Management')

@php use App\Http\Controllers\Admin\ProgramController; @endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Program Management</h2>
            <p class="text-body-md text-on-surface-variant">Organize the catalog of academic degrees and curricula.</p>
        </div>
        @can('programs.create')
            <a href="{{ route('programs.create') }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> Add New Program
            </a>
        @endcan
    </div>

    <div class="mb-lg grid grid-cols-1 gap-md md:grid-cols-3">
        @foreach ([
            ['Total Programs', $stats['total'], 'menu_book', 'bg-primary/10 text-primary'],
            ['Active', $stats['active'], 'check_circle', 'bg-tertiary/10 text-tertiary'],
            ['Departments', $stats['departments'], 'account_tree', 'bg-secondary-container text-on-secondary-container'],
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
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search programs or codes..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="degree_level" data-allow-clear placeholder="All Levels"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Levels</option>
            @foreach (ProgramController::DEGREE_LEVELS as $level)
                <option value="{{ $level }}" @selected(request('degree_level') === $level)>{{ $level }}</option>
            @endforeach
        </select>
        <select name="status" data-allow-clear placeholder="All Statuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Statuses</option>
            @foreach (ProgramController::STATUSES as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Program</th>
                        <th class="px-lg py-4 font-bold">Level</th>
                        <th class="px-lg py-4 font-bold">Department</th>
                        <th class="px-lg py-4 font-bold">Structure</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($programs as $program)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-4">
                                <div class="font-semibold text-on-surface">{{ $program->name }}</div>
                                <div class="text-label-sm text-on-surface-variant">{{ $program->code }} • {{ $program->courses_count }} {{ Str::plural('course', $program->courses_count) }}</div>
                            </td>
                            <td class="px-lg py-4">
                                @if ($program->degree_level)
                                    <span class="rounded-md bg-secondary-container px-2 py-1 text-label-sm text-on-secondary-container">{{ $program->degree_level }}</span>
                                @else <span class="text-outline">—</span> @endif
                            </td>
                            <td class="px-lg py-4 text-on-surface-variant">{{ $program->department?->name ?? '—' }}</td>
                            <td class="px-lg py-4 text-label-md text-on-surface-variant">
                                {{ $program->total_years ? rtrim(rtrim($program->total_years, '0'), '.').' yrs' : '—' }}
                                @if ($program->total_credits) • {{ $program->total_credits }} cr @endif
                            </td>
                            <td class="px-lg py-4">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-medium {{ ($program->status ?? 'active') === 'active' ? 'bg-tertiary/10 text-tertiary' : 'bg-outline-variant/40 text-on-surface-variant' }}">
                                    {{ ucfirst($program->status ?? 'active') }}
                                </span>
                            </td>
                            <td class="px-lg py-4">
                                <div class="flex items-center justify-end gap-1">
                                    @can('programs.edit')
                                        <a href="{{ route('programs.edit', $program) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endcan
                                    @can('programs.delete')
                                        <form method="POST" action="{{ route('programs.destroy', $program) }}"
                                              onsubmit="return confirm('Delete {{ $program->name }}?');">
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
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">menu_book</span>
                                No programs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
            {{ $programs->links() }}
        </div>
    </div>
@endsection
