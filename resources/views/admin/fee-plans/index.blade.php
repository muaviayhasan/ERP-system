@extends('layouts.admin')

@section('title', 'Fee Plans')

@php use App\Http\Controllers\Admin\FeePlanController; @endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Fee Plans</h2>
            <p class="text-body-md text-on-surface-variant">Payment schedules attached to fee structures.</p>
        </div>
        @can('fee-plans.create')
            <a href="{{ route('fee-plans.create') }}" class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> Add Plan
            </a>
        @endcan
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search plans..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="schedule_type" data-allow-clear placeholder="All Schedules"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary md:col-span-2">
            <option value="">All Schedules</option>
            @foreach (FeePlanController::SCHEDULE_TYPES as $type)
                <option value="{{ $type }}" @selected(request('schedule_type') === $type)>{{ Str::headline($type) }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Plan</th>
                        <th class="px-lg py-4 font-bold">Structure</th>
                        <th class="px-lg py-4 font-bold">Schedule</th>
                        <th class="px-lg py-4 font-bold">Payments</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($plans as $plan)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3 font-bold text-on-surface">{{ $plan->name }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $plan->feeStructure?->name ?? '—' }}</td>
                            <td class="px-lg py-3"><span class="rounded-md bg-secondary-container px-2 py-1 text-label-sm text-on-secondary-container">{{ Str::headline($plan->schedule_type) }}</span></td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $plan->number_of_payments ?? '—' }}</td>
                            <td class="px-lg py-3">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ ($plan->status ?? 'active') === 'active' ? 'bg-tertiary/10 text-tertiary' : 'bg-outline-variant/40 text-on-surface-variant' }}">{{ ucfirst($plan->status ?? 'active') }}</span>
                            </td>
                            <td class="px-lg py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('fee-plans.edit')<a href="{{ route('fee-plans.edit', $plan) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></a>@endcan
                                    @can('fee-plans.delete')
                                        <form method="POST" action="{{ route('fee-plans.destroy', $plan) }}" onsubmit="return confirm('Delete {{ $plan->name }}?');">@csrf @method('DELETE')<button type="submit" class="rounded-lg p-2 text-on-surface-variant hover:bg-error/10 hover:text-error" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button></form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-lg py-12 text-center text-on-surface-variant"><span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">event_repeat</span>No fee plans yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">{{ $plans->links() }}</div>
    </div>
@endsection
