@extends('layouts.admin')

@section('title', 'Installments')

@php
    use App\Http\Controllers\Admin\FeeInstallmentController;
    $statusStyles = ['paid' => 'bg-tertiary/10 text-tertiary', 'pending' => 'bg-orange-100 text-orange-600', 'overdue' => 'bg-error/10 text-error', 'waived' => 'bg-secondary-container text-on-secondary-container'];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Installment Management</h2>
            <p class="text-body-md text-on-surface-variant">Track scheduled installment payments.</p>
        </div>
        @can('fee-installments.create')
            <a href="{{ route('fee-installments.create') }}" class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> Add Installment
            </a>
        @endcan
    </div>

    <div class="mb-lg grid grid-cols-1 gap-md md:grid-cols-3">
        @foreach ([['Pending', $stats['pending'], 'hourglass_top', 'bg-orange-100 text-orange-600'], ['Paid', $stats['paid'], 'check_circle', 'bg-tertiary/10 text-tertiary'], ['Outstanding', format_money($stats['due_amount']), 'account_balance_wallet', 'bg-primary/10 text-primary']] as [$label, $value, $icon, $tone])
            <div class="flex items-center justify-between rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <div><p class="mb-1 text-label-sm uppercase tracking-wider text-on-surface-variant">{{ $label }}</p><h3 class="font-headline-md text-headline-md text-on-surface">{{ is_numeric($value) ? number_format($value) : $value }}</h3></div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $tone }}"><span class="material-symbols-outlined">{{ $icon }}</span></div>
            </div>
        @endforeach
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <select name="status" data-allow-clear placeholder="All Statuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary md:col-span-2">
            <option value="">All Statuses</option>
            @foreach (FeeInstallmentController::STATUSES as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-4 text-on-surface-variant hover:bg-surface-container-low"><span class="material-symbols-outlined align-middle">filter_list</span> Filter</button>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Student</th>
                        <th class="px-lg py-4 font-bold">#</th>
                        <th class="px-lg py-4 font-bold">Due Date</th>
                        <th class="px-lg py-4 font-bold">Amount</th>
                        <th class="px-lg py-4 font-bold">Paid</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($installments as $inst)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3">
                                <p class="font-bold text-on-surface">{{ $inst->studentFeeAssignment?->student?->full_name ?? '—' }}</p>
                                <p class="text-label-sm text-on-surface-variant">{{ $inst->label }}</p>
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $inst->installment_number }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ format_date($inst->due_date) }}</td>
                            <td class="px-lg py-3 font-medium text-on-surface">{{ format_money($inst->amount) }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ format_money($inst->amount_paid) }}</td>
                            <td class="px-lg py-3"><span class="rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $statusStyles[$inst->status] ?? '' }}">{{ ucfirst($inst->status) }}</span></td>
                            <td class="px-lg py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('fee-installments.edit')<a href="{{ route('fee-installments.edit', $inst) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></a>@endcan
                                    @can('fee-installments.delete')<form method="POST" action="{{ route('fee-installments.destroy', $inst) }}" onsubmit="return confirm('Delete this installment?');">@csrf @method('DELETE')<button type="submit" class="rounded-lg p-2 text-on-surface-variant hover:bg-error/10 hover:text-error" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button></form>@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-lg py-12 text-center text-on-surface-variant"><span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">calendar_view_week</span>No installments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">{{ $installments->links() }}</div>
    </div>
@endsection
