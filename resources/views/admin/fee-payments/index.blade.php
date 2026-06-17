@extends('layouts.admin')

@section('title', 'Fee Collection')

@php
    use App\Http\Controllers\Admin\FeePaymentController;
    $statusStyles = ['paid' => 'bg-tertiary/10 text-tertiary', 'partial' => 'bg-orange-100 text-orange-600', 'pending' => 'bg-primary/10 text-primary'];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Fee Collection</h2>
            <p class="text-body-md text-on-surface-variant">Process payments — each posts to the ledger and issues a receipt.</p>
        </div>
        @can('fee-payments.create')
            <a href="{{ route('fee-payments.create') }}" class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">point_of_sale</span> Collect Fee
            </a>
        @endcan
    </div>

    <div class="mb-lg grid grid-cols-2 gap-md lg:grid-cols-4">
        @foreach ([['Collected Today', format_money($stats['today']), 'today', 'bg-tertiary/10 text-tertiary'], ['This Month', format_money($stats['month']), 'calendar_month', 'bg-primary/10 text-primary'], ['Pending Dues', format_money($stats['pending']), 'pending_actions', 'bg-orange-100 text-orange-600'], ['Overdue Accounts', $stats['overdue'], 'error', 'bg-error/10 text-error']] as [$label, $value, $icon, $tone])
            <div class="flex items-center justify-between rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <div><p class="mb-1 text-label-sm uppercase tracking-wider text-on-surface-variant">{{ $label }}</p><h3 class="font-headline-md text-headline-md text-on-surface">{{ is_numeric($value) ? number_format($value) : $value }}</h3></div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $tone }}"><span class="material-symbols-outlined">{{ $icon }}</span></div>
            </div>
        @endforeach
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search receipts, students..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="method" data-allow-clear placeholder="All Methods"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary md:col-span-2">
            <option value="">All Methods</option>
            @foreach (FeePaymentController::METHODS as $m)<option value="{{ $m }}" @selected(request('method') === $m)>{{ ucfirst($m) }}</option>@endforeach
        </select>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="border-b border-outline-variant bg-surface-container-low px-lg py-3"><h3 class="text-label-md font-bold uppercase tracking-wider text-on-surface-variant">Recent Transactions</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-3 font-bold">Receipt #</th>
                        <th class="px-lg py-3 font-bold">Student</th>
                        <th class="px-lg py-3 font-bold">Paid</th>
                        <th class="px-lg py-3 font-bold">Balance</th>
                        <th class="px-lg py-3 font-bold">Method</th>
                        <th class="px-lg py-3 font-bold">Status</th>
                        <th class="px-lg py-3 text-right font-bold">Receipt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($payments as $payment)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3 font-medium text-on-surface">{{ $payment->receipt?->receipt_number ?? $payment->transaction_id }}</td>
                            <td class="px-lg py-3">
                                <p class="font-bold text-on-surface">{{ $payment->student?->full_name ?? '—' }}</p>
                                <p class="text-label-sm text-on-surface-variant">{{ $payment->studentFeeAssignment?->program?->name }}</p>
                            </td>
                            <td class="px-lg py-3 font-medium text-tertiary">{{ format_money($payment->amount_paid) }}</td>
                            <td class="px-lg py-3 {{ $payment->balance > 0 ? 'text-error' : 'text-on-surface-variant' }}">{{ format_money($payment->balance) }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ ucfirst($payment->payment_method) }}</td>
                            <td class="px-lg py-3"><span class="rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $statusStyles[$payment->status] ?? '' }}">{{ ucfirst($payment->status) }}</span></td>
                            <td class="px-lg py-3 text-right">
                                @if ($payment->receipt_id)
                                    <a href="{{ route('fee-receipts.show', $payment->receipt_id) }}" class="inline-flex rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="View receipt"><span class="material-symbols-outlined text-[20px]">receipt_long</span></a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-lg py-12 text-center text-on-surface-variant"><span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">point_of_sale</span>No payments collected yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">{{ $payments->links() }}</div>
    </div>
@endsection
