@extends('layouts.admin')

@section('title', 'Section Management')

@php use App\Http\Controllers\Admin\SectionController; @endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Section Management</h2>
            <p class="text-body-md text-on-surface-variant">Manage class sections, groups, and academic subdivisions.</p>
        </div>
        @can('sections.create')
            <a href="{{ route('sections.create') }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> Add Section
            </a>
        @endcan
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search sections or codes..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="class" data-allow-clear placeholder="All Classes"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Classes</option>
            @foreach ($classes as $class)
                <option value="{{ $class->id }}" @selected((int) request('class') === $class->id)>{{ $class->name }}</option>
            @endforeach
        </select>
        <select name="type" data-allow-clear placeholder="All Types"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Types</option>
            @foreach (SectionController::TYPES as $type)
                <option value="{{ $type }}" @selected(request('type') === $type)>{{ $type }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Section</th>
                        <th class="px-lg py-4 font-bold">Type</th>
                        <th class="px-lg py-4 font-bold">Class</th>
                        <th class="px-lg py-4 font-bold">Capacity</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($sections as $section)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-4">
                                <div class="font-semibold text-on-surface">{{ $section->name }}</div>
                                <div class="text-label-sm text-on-surface-variant">{{ $section->code }}</div>
                            </td>
                            <td class="px-lg py-4">
                                @if ($section->section_type)
                                    <span class="rounded-md bg-secondary-container px-2 py-1 text-label-sm text-on-secondary-container">{{ $section->section_type }}</span>
                                @else <span class="text-outline">—</span> @endif
                            </td>
                            <td class="px-lg py-4 text-on-surface-variant">{{ $section->schoolClass?->name ?? '—' }}</td>
                            <td class="px-lg py-4 text-on-surface-variant">
                                {{ $section->current_enrollment ?? 0 }} / {{ $section->max_capacity ?? '—' }}
                            </td>
                            <td class="px-lg py-4">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-medium {{ $section->is_active ? 'bg-tertiary/10 text-tertiary' : 'bg-outline-variant/40 text-on-surface-variant' }}">
                                    {{ $section->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-lg py-4">
                                <div class="flex items-center justify-end gap-1">
                                    @can('sections.edit')
                                        <a href="{{ route('sections.edit', $section) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endcan
                                    @can('sections.delete')
                                        <form method="POST" action="{{ route('sections.destroy', $section) }}" onsubmit="return confirm('Delete {{ $section->name }}?');">
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
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">grid_view</span>
                                No sections found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
            {{ $sections->links() }}
        </div>
    </div>
@endsection
