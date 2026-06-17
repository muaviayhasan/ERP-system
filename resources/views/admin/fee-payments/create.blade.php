@extends('layouts.admin')

@section('title', 'Collect Fee')

@php
    $currencySymbol = setting('finance', 'currency_symbol', '₨');
    $assignmentsData = $assignments->map(fn ($a) => [
        'id' => $a->id,
        'student' => $a->student?->full_name ?? 'Student #'.$a->student_id,
        'program' => $a->program?->name ?? '',
        'payable' => (float) $a->final_payable,
        'paid' => (float) $a->total_paid,
        'pending' => (float) $a->total_pending,
    ])->values();
@endphp

@section('content')
    <div class="mb-lg flex items-center gap-3">
        <a href="{{ route('fee-payments.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low"><span class="material-symbols-outlined">arrow_back</span></a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Quick Collection</h2>
            <p class="text-body-md text-on-surface-variant">Process a new payment — posts to the ledger and issues a receipt.</p>
        </div>
    </div>

    @if ($assignments->isEmpty())
        <div class="rounded-xl border border-dashed border-outline-variant bg-surface-container-lowest p-12 text-center text-on-surface-variant">
            <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">paid</span>
            No outstanding fee accounts. <a href="{{ route('student-fee-assignments.create') }}" class="text-primary hover:underline">Assign a fee plan</a> first.
        </div>
    @else
        <form method="POST" action="{{ route('fee-payments.store') }}"
              x-data="{
                  assignments: {{ Illuminate\Support\Js::from($assignmentsData) }},
                  selectedId: '{{ $selected }}',
                  method: 'cash',
                  amount: 0,
                  fmt(n) { return '{{ $currencySymbol }} ' + new Intl.NumberFormat(undefined, {minimumFractionDigits: 2}).format(n || 0) },
                  get current() { return this.assignments.find(a => String(a.id) === String(this.selectedId)) || null },
                  pick() { this.amount = this.current ? this.current.pending : 0 },
                  init() { this.pick() },
              }">
            @csrf
            <input type="hidden" name="payment_method" :value="method"/>
            <input type="hidden" name="amount_payable" :value="current ? current.pending : 0"/>

            <div class="grid grid-cols-1 gap-lg lg:grid-cols-3">
                {{-- Left: selection + breakdown --}}
                <div class="space-y-lg lg:col-span-2">
                    <x-settings.section title="Student Account" icon="badge">
                        <x-settings.field label="Fee Account" name="student_fee_assignment_id" required>
                            <select name="student_fee_assignment_id" x-model="selectedId" @change="pick()" required
                                    class="w-full rounded-lg border border-outline-variant bg-white px-md py-2.5 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">
                                <option value="">Select a student account...</option>
                                @foreach ($assignments as $a)
                                    <option value="{{ $a->id }}">{{ $a->student?->full_name ?? 'Student #'.$a->student_id }} — {{ format_money($a->total_pending) }} pending</option>
                                @endforeach
                            </select>
                        </x-settings.field>

                        <template x-if="current">
                            <div class="mt-md rounded-lg border border-outline-variant bg-surface-container-low p-md">
                                <p class="font-bold text-on-surface" x-text="current.student"></p>
                                <p class="text-label-sm text-on-surface-variant" x-text="current.program"></p>
                                <dl class="mt-3 space-y-1 text-body-md">
                                    <div class="flex justify-between"><dt class="text-on-surface-variant">Total Payable</dt><dd class="font-medium" x-text="fmt(current.payable)"></dd></div>
                                    <div class="flex justify-between"><dt class="text-on-surface-variant">Already Paid</dt><dd class="font-medium text-tertiary" x-text="fmt(current.paid)"></dd></div>
                                    <div class="flex justify-between border-t border-outline-variant pt-1"><dt class="font-bold text-on-surface">Total Pending</dt><dd class="font-bold text-error" x-text="fmt(current.pending)"></dd></div>
                                </dl>
                            </div>
                        </template>
                    </x-settings.section>

                    <x-settings.section title="Payment" icon="payments">
                        <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                            <x-settings.field label="Amount to Pay" name="amount_paid" required>
                                <x-settings.input type="number" step="0.01" min="0" name="amount_paid" x-model="amount" required/>
                            </x-settings.field>
                            <x-settings.field label="Reference / Remarks" name="reference_number">
                                <x-settings.input name="reference_number" maxlength="255" value="{{ old('reference_number') }}" placeholder="Optional reference code"/>
                            </x-settings.field>
                        </div>
                        <div class="mt-md">
                            <label class="mb-1 block text-label-sm font-bold text-on-surface-variant">Payment Method</label>
                            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                                @foreach ($methods as $m)
                                    <button type="button" @click="method = '{{ $m }}'"
                                            class="rounded-lg border px-4 py-3 text-label-md font-bold capitalize transition-colors"
                                            :class="method === '{{ $m }}' ? 'border-primary bg-primary/10 text-primary' : 'border-outline-variant text-on-surface-variant hover:bg-surface-container-low'">{{ $m }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="mt-md rounded-lg border border-outline-variant p-4">
                            <x-settings.toggle name="auto_allocate_installments" label="Auto-allocate to Installments" :checked="true"/>
                        </div>
                    </x-settings.section>
                </div>

                {{-- Right: summary --}}
                <aside>
                    <div class="sticky top-20 rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                        <h3 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">Summary</h3>
                        <dl class="space-y-2 text-body-md">
                            <div class="flex justify-between"><dt class="text-on-surface-variant">Pending</dt><dd class="font-medium" x-text="current ? fmt(current.pending) : '—'"></dd></div>
                            <div class="flex justify-between"><dt class="text-on-surface-variant">Paying Now</dt><dd class="font-medium" x-text="fmt(amount)"></dd></div>
                            <div class="flex justify-between border-t border-outline-variant pt-2"><dt class="font-bold text-on-surface">Remaining</dt><dd class="font-bold text-on-surface" x-text="current ? fmt(Math.max(current.pending - (parseFloat(amount) || 0), 0)) : '—'"></dd></div>
                        </dl>
                        <button type="submit" :disabled="!current || (parseFloat(amount) || 0) <= 0"
                                class="mt-lg w-full rounded-lg bg-primary px-lg py-3 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95 disabled:opacity-40">
                            Confirm &amp; Collect Payment
                        </button>
                        <p class="mt-2 text-center text-label-sm text-on-surface-variant">A receipt is generated automatically.</p>
                    </div>
                </aside>
            </div>
        </form>
    @endif
@endsection
