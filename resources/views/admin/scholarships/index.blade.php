@extends('layouts.admin')

@section('title', 'Scholarship Management')

@php
    use App\Http\Controllers\Admin\ScholarshipController;
    $typeStyles = ['merit' => 'bg-primary/10 text-primary', 'need' => 'bg-tertiary/10 text-tertiary', 'sports' => 'bg-orange-100 text-orange-600', 'institutional' => 'bg-secondary-container text-on-secondary-container'];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Scholarship Management</h2>
            <p class="text-body-md text-on-surface-variant">Manage student scholarships, discounts, and financial aid policies.</p>
        </div>
        <div class="flex items-center gap-2">
            @can('scholarship-assignments.create')
                <a href="{{ route('scholarship-assignments.create') }}" class="flex items-center gap-2 rounded-lg border border-outline-variant bg-surface-container-lowest px-lg py-2.5 text-label-md font-bold text-on-surface-variant hover:bg-surface-container-low">
                    <span class="material-symbols-outlined text-[18px]">person_add</span> Assign Scholarship
                </a>
            @endcan
            @can('scholarships.create')
                <a href="{{ route('scholarships.create') }}" class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                    <span class="material-symbols-outlined">add</span> Create Policy
                </a>
            @endcan
        </div>
    </div>

    <div class="mb-lg grid grid-cols-2 gap-md lg:grid-cols-4">
        @foreach ([['Active Scholarships', $stats['active'], 'volunteer_activism', 'bg-primary/10 text-primary'], ['Total Discount', format_money($stats['discount']), 'savings', 'bg-tertiary/10 text-tertiary'], ['Merit Policies', $stats['merit'], 'workspace_premium', 'bg-secondary-container text-on-secondary-container'], ['Need Policies', $stats['need'], 'diversity_3', 'bg-orange-100 text-orange-600']] as [$label, $value, $icon, $tone])
            <div class="flex items-center justify-between rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <div><p class="mb-1 text-label-sm uppercase tracking-wider text-on-surface-variant">{{ $label }}</p><h3 class="font-headline-md text-headline-md text-on-surface">{{ is_numeric($value) ? number_format($value) : $value }}</h3></div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $tone }}"><span class="material-symbols-outlined">{{ $icon }}</span></div>
            </div>
        @endforeach
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search scholarship policies..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="type" data-allow-clear placeholder="All Types"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Types</option>
            @foreach (ScholarshipController::TYPES as $type)<option value="{{ $type }}" @selected(request('type') === $type)>{{ ucfirst($type) }}</option>@endforeach
        </select>
        <select name="status" data-allow-clear placeholder="All Statuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Statuses</option>
            @foreach (ScholarshipController::STATUSES as $st)<option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>@endforeach
        </select>
    </form>

    {{-- Policies --}}
    <div class="mb-lg overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="border-b border-outline-variant bg-surface-container-low px-lg py-3"><h3 class="text-label-md font-bold uppercase tracking-wider text-on-surface-variant">Scholarship Policies</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-3 font-bold">Name &amp; Code</th>
                        <th class="px-lg py-3 font-bold">Type</th>
                        <th class="px-lg py-3 font-bold">Value</th>
                        <th class="px-lg py-3 font-bold">Awarded</th>
                        <th class="px-lg py-3 font-bold">Status</th>
                        <th class="px-lg py-3 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($scholarships as $scholarship)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3"><p class="font-bold text-on-surface">{{ $scholarship->name }}</p><p class="text-label-sm text-on-surface-variant">{{ $scholarship->code }}</p></td>
                            <td class="px-lg py-3"><span class="rounded-md px-2 py-1 text-label-sm font-bold {{ $typeStyles[$scholarship->type] ?? '' }}">{{ ucfirst($scholarship->type) }}</span></td>
                            <td class="px-lg py-3 font-medium text-on-surface">{{ $scholarship->value_type === 'percentage' ? rtrim(rtrim($scholarship->value, '0'), '.').'%' : format_money($scholarship->value) }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $scholarship->assignments_count }}</td>
                            <td class="px-lg py-3"><span class="rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $scholarship->status === 'active' ? 'bg-tertiary/10 text-tertiary' : 'bg-outline-variant/40 text-on-surface-variant' }}">{{ ucfirst($scholarship->status) }}</span></td>
                            <td class="px-lg py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('scholarships.edit')<a href="{{ route('scholarships.edit', $scholarship) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></a>@endcan
                                    @can('scholarships.delete')<form method="POST" action="{{ route('scholarships.destroy', $scholarship) }}" onsubmit="return confirm('Delete {{ $scholarship->name }}?');">@csrf @method('DELETE')<button type="submit" class="rounded-lg p-2 text-on-surface-variant hover:bg-error/10 hover:text-error" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button></form>@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-lg py-12 text-center text-on-surface-variant"><span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">volunteer_activism</span>No scholarship policies yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">{{ $scholarships->links() }}</div>
    </div>

    {{-- Active assignments --}}
    @if ($assignments->isNotEmpty())
        <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
            <div class="border-b border-outline-variant bg-surface-container-low px-lg py-3"><h3 class="text-label-md font-bold uppercase tracking-wider text-on-surface-variant">Active Assignments</h3></div>
            <div class="divide-y divide-outline-variant">
                @foreach ($assignments as $assignment)
                    <div class="flex items-center justify-between px-lg py-3">
                        <div>
                            <p class="font-bold text-on-surface">{{ $assignment->student?->full_name ?? '—' }}</p>
                            <p class="text-label-sm text-on-surface-variant">{{ $assignment->scholarship?->name }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-tertiary">- {{ format_money($assignment->discount_amount) }}</span>
                            <span class="rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $assignment->status === 'active' ? 'bg-tertiary/10 text-tertiary' : 'bg-outline-variant/40 text-on-surface-variant' }}">{{ ucfirst($assignment->status) }}</span>
                            @can('scholarship-assignments.delete')
                                <form method="POST" action="{{ route('scholarship-assignments.destroy', $assignment) }}" onsubmit="return confirm('Remove this assignment?');">@csrf @method('DELETE')<button type="submit" class="rounded-lg p-1.5 text-on-surface-variant hover:text-error" title="Remove"><span class="material-symbols-outlined text-[18px]">delete</span></button></form>
                            @endcan
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
