@extends('layouts.admin')

@section('title', 'Fee Structures')

@php
    use App\Http\Controllers\Admin\FeeStructureController;
    $statusStyles = ['active' => 'bg-tertiary/10 text-tertiary', 'draft' => 'bg-orange-100 text-orange-600', 'archived' => 'bg-outline-variant/40 text-on-surface-variant'];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Fee Structure Management</h2>
            <p class="text-body-md text-on-surface-variant">Build and manage complete fee plans for academic programs.</p>
        </div>
        @can('fee-structures.create')
            <a href="{{ route('fee-structures.create') }}" class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> Create Fee Structure
            </a>
        @endcan
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or code..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="campus" data-allow-clear placeholder="All Campuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Campuses</option>
            @foreach ($campuses as $campus)<option value="{{ $campus->id }}" @selected((int) request('campus') === $campus->id)>{{ $campus->name }}</option>@endforeach
        </select>
        <select name="status" data-allow-clear placeholder="All Statuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Statuses</option>
            @foreach (FeeStructureController::STATUSES as $st)<option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>@endforeach
        </select>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Name &amp; Code</th>
                        <th class="px-lg py-4 font-bold">Program</th>
                        <th class="px-lg py-4 font-bold">Cycle</th>
                        <th class="px-lg py-4 font-bold">Total Fee</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($structures as $structure)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3">
                                <p class="font-bold text-on-surface">{{ $structure->name }}</p>
                                <p class="text-label-sm text-on-surface-variant">{{ $structure->code }}</p>
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $structure->program?->name ?? $structure->level ?? '—' }}</td>
                            <td class="px-lg py-3"><span class="rounded-md bg-secondary-container px-2 py-1 text-label-sm text-on-secondary-container">{{ $structure->billing_cycle }}</span></td>
                            <td class="px-lg py-3 font-medium text-on-surface">{{ format_money($structure->total_fee) }}</td>
                            <td class="px-lg py-3"><span class="rounded-full px-2.5 py-0.5 text-label-sm font-bold uppercase {{ $statusStyles[$structure->status] ?? '' }}">{{ $structure->status }}</span></td>
                            <td class="px-lg py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('fee-structures.edit')<a href="{{ route('fee-structures.edit', $structure) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></a>@endcan
                                    @can('fee-structures.delete')<form method="POST" action="{{ route('fee-structures.destroy', $structure) }}" onsubmit="return confirm('Delete {{ $structure->name }}?');">@csrf @method('DELETE')<button type="submit" class="rounded-lg p-2 text-on-surface-variant hover:bg-error/10 hover:text-error" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button></form>@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-lg py-12 text-center text-on-surface-variant"><span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">receipt_long</span>No fee structures yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">{{ $structures->links() }}</div>
    </div>
@endsection
