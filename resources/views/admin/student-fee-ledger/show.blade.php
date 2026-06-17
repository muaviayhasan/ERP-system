@extends('layouts.admin')

@section('title', 'Fee Ledger')

@php
    $name = $student->full_name ?: trim($student->first_name.' '.$student->last_name);
    $typeStyles = ['fee' => 'bg-primary/10 text-primary', 'payment' => 'bg-tertiary/10 text-tertiary', 'scholarship' => 'bg-secondary-container text-on-secondary-container', 'fine' => 'bg-error/10 text-error', 'discount' => 'bg-orange-100 text-orange-600'];
    $progress = $summary['payable'] > 0 ? min(100, round($summary['paid'] / $summary['payable'] * 100)) : 0;
@endphp

@section('content')
    <div class="mb-lg flex items-center gap-3">
        <a href="{{ route('student-fee-ledger.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low"><span class="material-symbols-outlined">arrow_back</span></a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Student Fee Ledger</h2>
            <p class="text-body-md text-on-surface-variant">{{ $name }} · {{ $student->student_code }} · {{ $student->program?->name }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-lg lg:grid-cols-3">
        {{-- Transaction history --}}
        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
                <div class="border-b border-outline-variant bg-surface-container-low px-lg py-3"><h3 class="text-label-md font-bold uppercase tracking-wider text-on-surface-variant">Transaction History</h3></div>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead class="border-b border-outline-variant">
                            <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                                <th class="px-lg py-3 font-bold">Date</th>
                                <th class="px-lg py-3 font-bold">Type</th>
                                <th class="px-lg py-3 font-bold">Description</th>
                                <th class="px-lg py-3 text-right font-bold">Debit</th>
                                <th class="px-lg py-3 text-right font-bold">Credit</th>
                                <th class="px-lg py-3 text-right font-bold">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @forelse ($entries as $entry)
                                <tr>
                                    <td class="px-lg py-3 text-on-surface-variant">{{ format_date($entry->entry_date) }}</td>
                                    <td class="px-lg py-3"><span class="rounded-md px-2 py-0.5 text-label-sm font-bold {{ $typeStyles[$entry->transaction_type] ?? 'bg-surface-container-high text-on-surface-variant' }}">{{ ucfirst($entry->transaction_type) }}</span></td>
                                    <td class="px-lg py-3 text-on-surface">{{ $entry->description ?? '—' }}</td>
                                    <td class="px-lg py-3 text-right text-on-surface-variant">{{ $entry->debit > 0 ? format_money($entry->debit) : '—' }}</td>
                                    <td class="px-lg py-3 text-right text-tertiary">{{ $entry->credit > 0 ? format_money($entry->credit) : '—' }}</td>
                                    <td class="px-lg py-3 text-right font-medium text-on-surface">{{ format_money($entry->balance) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-lg py-12 text-center text-on-surface-variant"><span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">history</span>No ledger entries yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <aside class="space-y-lg">
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <h3 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">Fee Breakdown</h3>
                <dl class="space-y-2 text-body-md">
                    <div class="flex justify-between"><dt class="text-on-surface-variant">Total Payable</dt><dd class="font-medium text-on-surface">{{ format_money($summary['payable']) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-on-surface-variant">Scholarship</dt><dd class="font-medium text-tertiary">- {{ format_money($summary['scholarship']) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-on-surface-variant">Total Paid</dt><dd class="font-medium text-tertiary">{{ format_money($summary['paid']) }}</dd></div>
                    <div class="flex justify-between border-t border-outline-variant pt-2"><dt class="font-bold text-on-surface">Pending</dt><dd class="font-bold text-error">{{ format_money($summary['pending']) }}</dd></div>
                </dl>
            </div>
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <div class="mb-2 flex items-center justify-between">
                    <h3 class="text-label-md font-bold uppercase tracking-widest text-primary">Payment Progress</h3>
                    <span class="font-bold text-primary">{{ $progress }}%</span>
                </div>
                <div class="h-3 w-full overflow-hidden rounded-full bg-surface-container-high">
                    <div class="h-full bg-tertiary transition-all" style="width: {{ $progress }}%"></div>
                </div>
                <p class="mt-2 text-label-sm text-on-surface-variant">{{ format_money($summary['paid']) }} of {{ format_money($summary['payable']) }} collected.</p>
                @can('fee-payments.create')
                    @if ($assignments->first())
                        <a href="{{ route('fee-payments.create', ['assignment' => $assignments->first()->id]) }}" class="mt-md flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary hover:opacity-90">
                            <span class="material-symbols-outlined text-[18px]">point_of_sale</span> Collect Payment
                        </a>
                    @endif
                @endcan
            </div>
        </aside>
    </div>
@endsection
