@extends('layouts.admin')

@section('title', 'Teacher Management')

@php
    use App\Http\Controllers\Admin\TeacherController;
    $statusStyles = [
        'active' => 'bg-tertiary/10 text-tertiary',
        'inactive' => 'bg-outline-variant/40 text-on-surface-variant',
    ];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Teacher Management</h2>
            <p class="text-body-md text-on-surface-variant">Manage faculty, assignments, and teaching workload.</p>
        </div>
        @can('teachers.create')
            <a href="{{ route('teachers.create') }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">person_add</span> Add Teacher
            </a>
        @endcan
    </div>

    <div class="mb-lg grid grid-cols-1 gap-md md:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['Total Faculty', $stats['total'], 'groups', 'bg-primary/10 text-primary'],
            ['Active Faculty', $stats['active'], 'check_circle', 'bg-tertiary/10 text-tertiary'],
            ['Department Heads', $stats['heads'], 'workspace_premium', 'bg-secondary-container text-on-secondary-container'],
            ['Departments', $stats['departments'], 'account_tree', 'bg-primary-fixed text-on-primary-fixed'],
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
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search teachers, departments, or roles..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="department" data-allow-clear placeholder="All Departments"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Departments</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @selected((int) request('department') === $department->id)>{{ $department->name }}</option>
            @endforeach
        </select>
        <select name="status" data-allow-clear placeholder="All Statuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Statuses</option>
            @foreach (TeacherController::STATUSES as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Teacher</th>
                        <th class="px-lg py-4 font-bold">Code</th>
                        <th class="px-lg py-4 font-bold">Department</th>
                        <th class="px-lg py-4 font-bold">Programs</th>
                        <th class="px-lg py-4 font-bold">Assignments</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($teachers as $teacher)
                        @php
                            $name = $teacher->full_name ?: trim($teacher->first_name.' '.$teacher->last_name);
                            $initials = Str::of($name)->explode(' ')->take(2)->map(fn ($w) => Str::substr($w, 0, 1))->implode('');
                        @endphp
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3">
                                <div class="flex items-center gap-3">
                                    @if ($teacher->photo_url)
                                        <img src="{{ Storage::url($teacher->photo_url) }}" alt="{{ $name }}" class="h-10 w-10 rounded-full object-cover"/>
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-container font-bold text-on-primary">{{ Str::upper($initials) ?: 'T' }}</div>
                                    @endif
                                    <div>
                                        <a href="{{ route('teachers.show', $teacher) }}" class="font-bold text-on-surface hover:text-primary">{{ $name }}</a>
                                        <p class="text-label-sm text-on-surface-variant">{{ $teacher->designation }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $teacher->teacher_code }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $teacher->department?->name ?? '—' }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $teacher->programs->pluck('name')->join(', ') ?: '—' }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $teacher->assignments_count }}</td>
                            <td class="px-lg py-3">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $statusStyles[$teacher->status] ?? $statusStyles['inactive'] }}">
                                    {{ ucfirst($teacher->status ?? 'inactive') }}
                                </span>
                            </td>
                            <td class="px-lg py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('teachers.show', $teacher) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="View profile">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                    @can('teachers.edit')
                                        <a href="{{ route('teachers.edit', $teacher) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endcan
                                    @can('teachers.delete')
                                        <form method="POST" action="{{ route('teachers.destroy', $teacher) }}" onsubmit="return confirm('Delete {{ $name }}?');">
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
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">person_off</span>
                                No teachers found. <a href="{{ route('teachers.create') }}" class="text-primary hover:underline">Add one</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
            {{ $teachers->links() }}
        </div>
    </div>
@endsection
