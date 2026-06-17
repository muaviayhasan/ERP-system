@extends('layouts.admin')

@section('title', 'Pending Fees')

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Pending Fee Management</h2>
            <p class="text-body-md text-on-surface-variant">Outstanding dues across all students.</p>
        </div>
    </div>

    <div class="mb-lg grid grid-cols-1 gap-md md:grid-cols-3">
        @foreach ([['Students with Dues', $stats['students'], 'group', 'bg-primary/10 text-primary'], ['Total Outstanding', format_money($stats['amount']), 'account_balance_wallet', 'bg-error/10 text-error'], ['Overdue Accounts', $stats['overdue'], 'event_busy', 'bg-orange-100 text-orange-600']] as [$label, $value, $icon, $tone])
            <div class="flex items-center justify-between rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <div><p class="mb-1 text-label-sm uppercase tracking-wider text-on-surface-variant">{{ $label }}</p><h3 class="font-headline-md text-headline-md text-on-surface">{{ is_numeric($value) ? number_format($value) : $value }}</h3></div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $tone }}"><span class="material-symbols-outlined">{{ $icon }}</span></div>
            </div>
        @endforeach
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search student..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="program" data-allow-clear placeholder="All Programs"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Programs</option>
            @foreach ($programs as $program)<option value="{{ $program->id }}" @selected((int) request('program') === $program->id)>{{ $program->name }}</option>@endforeach
        </select>
        <label class="flex items-center gap-2 rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md">
            <input type="checkbox" name="overdue" value="1" @checked(request('overdue')) onchange="this.form.submit()"/> Overdue only
        </label>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Student</th>
                        <th class="px-lg py-4 font-bold">Program</th>
                        <th class="px-lg py-4 font-bold">Pending</th>
                        <th class="px-lg py-4 font-bold">Due Date</th>
                        <th class="px-lg py-4 font-bold">Overdue</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($pendingFees as $pending)
                        @php $overdue = $pending->due_date && $pending->due_date->isPast(); @endphp
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3">
                                <p class="font-bold text-on-surface">{{ $pending->student?->full_name ?? '—' }}</p>
                                <p class="text-label-sm text-on-surface-variant">{{ $pending->student?->student_code }}</p>
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $pending->program?->name ?? '—' }}</td>
                            <td class="px-lg py-3 font-bold text-error">{{ format_money($pending->amount_pending) }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $pending->due_date ? format_date($pending->due_date) : '—' }}</td>
                            <td class="px-lg py-3">
                                @if ($overdue)
                                    <span class="rounded-full bg-error/10 px-2.5 py-0.5 text-label-sm font-bold text-error">{{ $pending->due_date->diffInDays(now()) }} days</span>
                                @else
                                    <span class="text-label-sm text-on-surface-variant">On track</span>
                                @endif
                            </td>
                            <td class="px-lg py-3 text-right">
                                @can('fee-payments.create')
                                    <a href="{{ route('fee-payments.create', ['assignment' => $pending->student_fee_assignment_id]) }}" class="inline-flex items-center gap-1 rounded-lg bg-primary px-3 py-1.5 text-label-sm font-bold text-on-primary hover:opacity-90">
                                        <span class="material-symbols-outlined text-[16px]">point_of_sale</span> Collect
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-lg py-12 text-center text-on-surface-variant"><span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">celebration</span>No pending fees. Everyone is paid up!</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">{{ $pendingFees->links() }}</div>
    </div>
@endsection
