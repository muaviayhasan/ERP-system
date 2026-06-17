@extends('layouts.admin')

@section('title', 'Student Fee Ledger')

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Student Fee Ledger</h2>
            <p class="text-body-md text-on-surface-variant">Per-student financial history and transaction tracking.</p>
        </div>
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-3">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for student name or ID..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <button type="submit" class="rounded-xl border border-outline-variant bg-surface-container-lowest px-4 text-on-surface-variant hover:bg-surface-container-low"><span class="material-symbols-outlined align-middle">search</span> Search</button>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Student</th>
                        <th class="px-lg py-4 font-bold">Program</th>
                        <th class="px-lg py-4 font-bold">Payable</th>
                        <th class="px-lg py-4 font-bold">Paid</th>
                        <th class="px-lg py-4 font-bold">Pending</th>
                        <th class="px-lg py-4 text-right font-bold">Ledger</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($accounts as $account)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3">
                                <p class="font-bold text-on-surface">{{ $account->student?->full_name ?? '—' }}</p>
                                <p class="text-label-sm text-on-surface-variant">{{ $account->student?->student_code }}</p>
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $account->program?->name ?? '—' }}</td>
                            <td class="px-lg py-3 font-medium text-on-surface">{{ format_money($account->final_payable) }}</td>
                            <td class="px-lg py-3 text-tertiary">{{ format_money($account->total_paid) }}</td>
                            <td class="px-lg py-3 font-medium text-error">{{ format_money($account->total_pending) }}</td>
                            <td class="px-lg py-3 text-right">
                                @if ($account->student)
                                    <a href="{{ route('student-fee-ledger.show', $account->student) }}" class="inline-flex items-center gap-1 rounded-lg border border-outline-variant px-3 py-1.5 text-label-sm font-bold text-primary hover:bg-surface-container-low">
                                        <span class="material-symbols-outlined text-[16px]">menu_book</span> View Ledger
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-lg py-12 text-center text-on-surface-variant"><span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">menu_book</span>No fee accounts yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">{{ $accounts->links() }}</div>
    </div>
@endsection
