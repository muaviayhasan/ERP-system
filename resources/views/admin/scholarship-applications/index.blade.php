@extends('layouts.admin')

@section('title', 'Scholarship Approval')

@php
    use App\Http\Controllers\Admin\ScholarshipApplicationController;
    $statusStyles = [
        'pending' => 'bg-orange-100 text-orange-600', 'under_review' => 'bg-primary/10 text-primary',
        'approved' => 'bg-tertiary/10 text-tertiary', 'rejected' => 'bg-error/10 text-error',
        'changes_requested' => 'bg-secondary-container text-on-secondary-container',
    ];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Scholarship Approval</h2>
            <p class="text-body-md text-on-surface-variant">Review and approve student scholarship applications.</p>
        </div>
        @can('scholarship-applications.create')
            <a href="{{ route('scholarship-applications.create') }}" class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> New Application
            </a>
        @endcan
    </div>

    <div class="mb-lg grid grid-cols-2 gap-md lg:grid-cols-4">
        @foreach ([['Pending Review', $stats['pending'], 'pending_actions', 'bg-orange-100 text-orange-600'], ['Approved', $stats['approved'], 'check_circle', 'bg-tertiary/10 text-tertiary'], ['Rejected', $stats['rejected'], 'cancel', 'bg-error/10 text-error'], ['Approved Value', format_money($stats['value']), 'savings', 'bg-primary/10 text-primary']] as [$label, $value, $icon, $tone])
            <div class="flex items-center justify-between rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <div><p class="mb-1 text-label-sm uppercase tracking-wider text-on-surface-variant">{{ $label }}</p><h3 class="font-headline-md text-headline-md text-on-surface">{{ is_numeric($value) ? number_format($value) : $value }}</h3></div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $tone }}"><span class="material-symbols-outlined">{{ $icon }}</span></div>
            </div>
        @endforeach
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-3">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search applications..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="status" data-allow-clear placeholder="All Statuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Statuses</option>
            @foreach (ScholarshipApplicationController::STATUSES as $st)<option value="{{ $st }}" @selected(request('status') === $st)>{{ Str::headline($st) }}</option>@endforeach
        </select>
        <button type="submit" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-4 text-on-surface-variant hover:bg-surface-container-low"><span class="material-symbols-outlined align-middle">filter_list</span> Filter</button>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Student</th>
                        <th class="px-lg py-4 font-bold">Scholarship</th>
                        <th class="px-lg py-4 font-bold">Requested</th>
                        <th class="px-lg py-4 font-bold">CGPA</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Review</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($applications as $app)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3">
                                <p class="font-bold text-on-surface">{{ $app->student?->full_name ?? '—' }}</p>
                                <p class="text-label-sm text-on-surface-variant">{{ $app->program?->name }}</p>
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $app->scholarship?->name ?? ucfirst($app->type) }}</td>
                            <td class="px-lg py-3 font-medium text-on-surface">{{ $app->requested_value ? format_money($app->requested_value) : ($app->requested_discount_percent ? rtrim(rtrim($app->requested_discount_percent, '0'), '.').'%' : '—') }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $app->cgpa ?? '—' }}</td>
                            <td class="px-lg py-3"><span class="rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $statusStyles[$app->status] ?? '' }}">{{ Str::headline($app->status) }}</span></td>
                            <td class="px-lg py-3 text-right">
                                <a href="{{ route('scholarship-applications.show', $app) }}" class="inline-flex items-center gap-1 rounded-lg border border-outline-variant px-3 py-1.5 text-label-sm font-bold text-primary hover:bg-surface-container-low">
                                    <span class="material-symbols-outlined text-[16px]">fact_check</span> Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-lg py-12 text-center text-on-surface-variant"><span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">how_to_reg</span>No applications to review.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">{{ $applications->links() }}</div>
    </div>
@endsection
