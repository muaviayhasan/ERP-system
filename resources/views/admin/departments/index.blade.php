@extends('layouts.admin')

@section('title', 'Department Management')

@php use App\Http\Controllers\Admin\DepartmentController; @endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Department Management</h2>
            <p class="text-body-md text-on-surface-variant">Manage academic departments across the institute structure.</p>
        </div>
        @can('departments.create')
            <a href="{{ route('departments.create') }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> Add Department
            </a>
        @endcan
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Filter by name or code..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="type" data-allow-clear placeholder="All Types"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Types</option>
            @foreach (DepartmentController::TYPES as $type)
                <option value="{{ $type }}" @selected(request('type') === $type)>{{ $type }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-4 text-on-surface-variant hover:bg-surface-container-low">
            <span class="material-symbols-outlined align-middle">filter_list</span> Filter
        </button>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Department</th>
                        <th class="px-lg py-4 font-bold">Type</th>
                        <th class="px-lg py-4 font-bold">Campuses</th>
                        <th class="px-lg py-4 font-bold">Programs</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($departments as $department)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-4">
                                <div class="flex items-center gap-md">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 font-bold text-primary">
                                        <span class="material-symbols-outlined text-[20px]">account_tree</span>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-on-surface">{{ $department->name }}</div>
                                        <div class="text-label-sm text-on-surface-variant">{{ $department->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-4 text-on-surface-variant">{{ $department->institution_type ?? '—' }}</td>
                            <td class="px-lg py-4 text-on-surface-variant">{{ $department->campuses->pluck('name')->join(', ') ?: '—' }}</td>
                            <td class="px-lg py-4 text-on-surface-variant">{{ $department->programs_count }}</td>
                            <td class="px-lg py-4">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-medium {{ $department->is_active ? 'bg-tertiary/10 text-tertiary' : 'bg-outline-variant/40 text-on-surface-variant' }}">
                                    {{ $department->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-lg py-4">
                                <div class="flex items-center justify-end gap-1">
                                    @can('departments.edit')
                                        <a href="{{ route('departments.edit', $department) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endcan
                                    @can('departments.delete')
                                        <form method="POST" action="{{ route('departments.destroy', $department) }}"
                                              onsubmit="return confirm('Delete {{ $department->name }}?');">
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
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">account_tree</span>
                                No departments found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
            {{ $departments->links() }}
        </div>
    </div>
@endsection
