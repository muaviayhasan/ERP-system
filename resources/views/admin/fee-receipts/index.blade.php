@extends('layouts.admin')

@section('title', 'Fee Receipts')

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Fee Receipts</h2>
            <p class="text-body-md text-on-surface-variant">Issued receipts for collected payments.</p>
        </div>
    </div>

    <div class="mb-lg grid grid-cols-1 gap-md md:grid-cols-2">
        @foreach ([['Total Receipts', $stats['total'], 'receipt_long', 'bg-primary/10 text-primary'], ['Total Collected', format_money($stats['collected']), 'payments', 'bg-tertiary/10 text-tertiary']] as [$label, $value, $icon, $tone])
            <div class="flex items-center justify-between rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <div><p class="mb-1 text-label-sm uppercase tracking-wider text-on-surface-variant">{{ $label }}</p><h3 class="font-headline-md text-headline-md text-on-surface">{{ is_numeric($value) ? number_format($value) : $value }}</h3></div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $tone }}"><span class="material-symbols-outlined">{{ $icon }}</span></div>
            </div>
        @endforeach
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-3">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search receipt number or student..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <button type="submit" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-4 text-on-surface-variant hover:bg-surface-container-low"><span class="material-symbols-outlined align-middle">search</span> Search</button>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Receipt #</th>
                        <th class="px-lg py-4 font-bold">Student</th>
                        <th class="px-lg py-4 font-bold">Paid</th>
                        <th class="px-lg py-4 font-bold">Balance</th>
                        <th class="px-lg py-4 font-bold">Issued</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($receipts as $receipt)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3 font-medium text-on-surface">{{ $receipt->receipt_number }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $receipt->student?->full_name ?? '—' }}</td>
                            <td class="px-lg py-3 font-medium text-tertiary">{{ format_money($receipt->amount_paid) }}</td>
                            <td class="px-lg py-3 {{ $receipt->balance > 0 ? 'text-error' : 'text-on-surface-variant' }}">{{ format_money($receipt->balance) }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ format_date($receipt->issued_at) }}</td>
                            <td class="px-lg py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('fee-receipts.show', $receipt) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="View"><span class="material-symbols-outlined text-[20px]">visibility</span></a>
                                    @can('fee-receipts.delete')<form method="POST" action="{{ route('fee-receipts.destroy', $receipt) }}" onsubmit="return confirm('Delete this receipt?');">@csrf @method('DELETE')<button type="submit" class="rounded-lg p-2 text-on-surface-variant hover:bg-error/10 hover:text-error" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button></form>@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-lg py-12 text-center text-on-surface-variant"><span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">receipt</span>No receipts yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">{{ $receipts->links() }}</div>
    </div>
@endsection
