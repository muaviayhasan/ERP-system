@extends('layouts.admin')

@section('title', 'Receipt '.$receipt->receipt_number)

@section('content')
    <div class="mb-lg flex items-center justify-between gap-3 print:hidden">
        <div class="flex items-center gap-3">
            <a href="{{ route('fee-receipts.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low"><span class="material-symbols-outlined">arrow_back</span></a>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Receipt</h2>
        </div>
        <button onclick="window.print()" class="flex items-center gap-2 rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
            <span class="material-symbols-outlined text-[18px]">print</span> Print
        </button>
    </div>

    <div class="mx-auto max-w-2xl rounded-xl border border-outline-variant bg-surface-container-lowest p-xl shadow-sm">
        {{-- Header --}}
        <div class="flex items-start justify-between border-b border-outline-variant pb-lg">
            <div>
                <h3 class="font-headline-lg text-headline-lg text-primary">{{ setting('general', 'institution_name', config('app.name')) }}</h3>
                <p class="text-body-md text-on-surface-variant">Official Fee Receipt</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-on-surface">{{ $receipt->receipt_number }}</p>
                <p class="text-label-sm text-on-surface-variant">{{ format_date($receipt->issued_at) }}</p>
                <span class="mt-1 inline-flex rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $receipt->balance > 0 ? 'bg-orange-100 text-orange-600' : 'bg-tertiary/10 text-tertiary' }}">{{ ucfirst($receipt->status) }}</span>
            </div>
        </div>

        {{-- Parties --}}
        <div class="grid grid-cols-1 gap-lg border-b border-outline-variant py-lg sm:grid-cols-2">
            <div>
                <p class="text-label-sm uppercase tracking-wider text-on-surface-variant">Student</p>
                <p class="font-bold text-on-surface">{{ $receipt->student?->full_name ?? '—' }}</p>
                <p class="text-label-sm text-on-surface-variant">{{ $receipt->student?->student_code }}</p>
                <p class="text-label-sm text-on-surface-variant">{{ $receipt->program?->name ?? $receipt->student?->program?->name }}</p>
            </div>
            <div class="sm:text-right">
                <p class="text-label-sm uppercase tracking-wider text-on-surface-variant">Transaction</p>
                <p class="font-medium text-on-surface">{{ $receipt->transaction_id }}</p>
                <p class="text-label-sm text-on-surface-variant">Method: {{ ucfirst($receipt->payment_method ?? '—') }}</p>
                @if ($receipt->reference_number)<p class="text-label-sm text-on-surface-variant">Ref: {{ $receipt->reference_number }}</p>@endif
            </div>
        </div>

        {{-- Amounts --}}
        <div class="space-y-2 py-lg">
            <div class="flex justify-between text-body-md"><span class="text-on-surface-variant">Total Payable</span><span class="font-medium text-on-surface">{{ format_money($receipt->total_payable) }}</span></div>
            <div class="flex justify-between text-body-md"><span class="text-on-surface-variant">Amount Paid</span><span class="font-bold text-tertiary">{{ format_money($receipt->amount_paid) }}</span></div>
            <div class="flex justify-between border-t border-outline-variant pt-2 text-body-lg"><span class="font-bold text-on-surface">Balance</span><span class="font-bold {{ $receipt->balance > 0 ? 'text-error' : 'text-on-surface' }}">{{ format_money($receipt->balance) }}</span></div>
        </div>

        <div class="flex items-center justify-between border-t border-outline-variant pt-lg text-label-sm text-on-surface-variant">
            <span>Collected by: {{ $receipt->collectedBy?->name ?? 'System' }}</span>
            <span>Generated {{ format_datetime($receipt->created_at) }}</span>
        </div>
    </div>
@endsection
