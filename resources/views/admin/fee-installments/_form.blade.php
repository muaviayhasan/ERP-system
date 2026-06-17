@php
    use App\Http\Controllers\Admin\FeeInstallmentController;
    $i = $installment ?? null;
    $due = old('due_date', isset($i) ? $i->due_date?->format('Y-m-d') : '');
    $paidAt = old('paid_at', isset($i) ? $i->paid_at?->format('Y-m-d') : '');
@endphp

<x-settings.section title="Installment Details" icon="calendar_view_week">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Student Fee Account" name="student_fee_assignment_id" class="md:col-span-2">
            <x-settings.select name="student_fee_assignment_id" data-allow-clear placeholder="Select an account...">
                <option value="">Select an account...</option>
                @foreach ($assignments as $id => $label)
                    <option value="{{ $id }}" @selected((int) old('student_fee_assignment_id', $i->student_fee_assignment_id ?? 0) === $id)>{{ $label }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Installment Number" name="installment_number" required>
            <x-settings.input type="number" min="1" name="installment_number" required value="{{ old('installment_number', $i->installment_number ?? 1) }}"/>
        </x-settings.field>
        <x-settings.field label="Label" name="label">
            <x-settings.input name="label" maxlength="255" value="{{ old('label', $i->label ?? '') }}" placeholder="Enrollment Deposit"/>
        </x-settings.field>
        <x-settings.field label="Due Date" name="due_date" required>
            <x-settings.input type="date" name="due_date" required value="{{ $due }}"/>
        </x-settings.field>
        <x-settings.field label="Percentage" name="percentage">
            <x-settings.input type="number" step="0.01" min="0" max="100" name="percentage" value="{{ old('percentage', $i->percentage ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Amount" name="amount" required>
            <x-settings.input type="number" step="0.01" min="0" name="amount" required value="{{ old('amount', $i->amount ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Amount Paid" name="amount_paid">
            <x-settings.input type="number" step="0.01" min="0" name="amount_paid" value="{{ old('amount_paid', $i->amount_paid ?? 0) }}"/>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (FeeInstallmentController::STATUSES as $st)
                    <option value="{{ $st }}" @selected(old('status', $i->status ?? 'pending') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Paid On" name="paid_at">
            <x-settings.input type="date" name="paid_at" value="{{ $paidAt }}"/>
        </x-settings.field>
    </div>
</x-settings.section>
