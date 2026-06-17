@extends('layouts.admin')

@section('title', 'Student Fee Assignment')

@php
    use App\Http\Controllers\Admin\StudentFeeAssignmentController;
    $statusStyles = ['active' => 'bg-tertiary/10 text-tertiary', 'paid' => 'bg-tertiary/10 text-tertiary', 'partial' => 'bg-orange-100 text-orange-600', 'pending' => 'bg-primary/10 text-primary', 'hold' => 'bg-error/10 text-error'];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Student Fee Assignment</h2>
            <p class="text-body-md text-on-surface-variant">Assign fee structures and configure billing per student.</p>
        </div>
        @can('student-fee-assignments.create')
            <a href="{{ route('student-fee-assignments.create') }}" class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">assignment_turned_in</span> Assign Fee Plan
            </a>
        @endcan
    </div>

    <div class="mb-lg grid grid-cols-2 gap-md lg:grid-cols-4">
        @foreach ([['Accounts', $stats['total'], 'badge', 'bg-primary/10 text-primary'], ['Total Payable', format_money($stats['payable']), 'request_quote', 'bg-secondary-container text-on-secondary-container'], ['Collected', format_money($stats['collected']), 'payments', 'bg-tertiary/10 text-tertiary'], ['Pending', format_money($stats['pending']), 'pending_actions', 'bg-error/10 text-error']] as [$label, $value, $icon, $tone])
            <div class="flex items-center justify-between rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <div><p class="mb-1 text-label-sm uppercase tracking-wider text-on-surface-variant">{{ $label }}</p><h3 class="font-headline-md text-headline-md text-on-surface">{{ is_numeric($value) ? number_format($value) : $value }}</h3></div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $tone }}"><span class="material-symbols-outlined">{{ $icon }}</span></div>
            </div>
        @endforeach
    </div>

    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search student name or ID..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="status" data-allow-clear placeholder="All Statuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary md:col-span-2">
            <option value="">All Statuses</option>
            @foreach (StudentFeeAssignmentController::STATUSES as $st)<option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>@endforeach
        </select>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Student</th>
                        <th class="px-lg py-4 font-bold">Fee Plan</th>
                        <th class="px-lg py-4 font-bold">Payable</th>
                        <th class="px-lg py-4 font-bold">Paid</th>
                        <th class="px-lg py-4 font-bold">Pending</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($assignments as $assignment)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3">
                                <p class="font-bold text-on-surface">{{ $assignment->student?->full_name ?? '—' }}</p>
                                <p class="text-label-sm text-on-surface-variant">{{ $assignment->program?->name }}</p>
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $assignment->feePlan?->name ?? $assignment->feeStructure?->name ?? '—' }}</td>
                            <td class="px-lg py-3 font-medium text-on-surface">{{ format_money($assignment->final_payable) }}</td>
                            <td class="px-lg py-3 text-tertiary">{{ format_money($assignment->total_paid) }}</td>
                            <td class="px-lg py-3 font-medium text-error">{{ format_money($assignment->total_pending) }}</td>
                            <td class="px-lg py-3"><span class="rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $statusStyles[$assignment->status] ?? '' }}">{{ ucfirst($assignment->status) }}</span></td>
                            <td class="px-lg py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('fee-payments.create')<a href="{{ route('fee-payments.create', ['assignment' => $assignment->id]) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-tertiary" title="Collect"><span class="material-symbols-outlined text-[20px]">point_of_sale</span></a>@endcan
                                    @can('student-fee-assignments.edit')<a href="{{ route('student-fee-assignments.edit', $assignment) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit"><span class="material-symbols-outlined text-[20px]">edit</span></a>@endcan
                                    @can('student-fee-assignments.delete')<form method="POST" action="{{ route('student-fee-assignments.destroy', $assignment) }}" onsubmit="return confirm('Delete this assignment?');">@csrf @method('DELETE')<button type="submit" class="rounded-lg p-2 text-on-surface-variant hover:bg-error/10 hover:text-error" title="Delete"><span class="material-symbols-outlined text-[20px]">delete</span></button></form>@endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-lg py-12 text-center text-on-surface-variant"><span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">assignment_turned_in</span>No fee assignments yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">{{ $assignments->links() }}</div>
    </div>
@endsection
