@php
    use App\Http\Controllers\Admin\FeePlanController;
    $p = $plan ?? null;
    $start = old('start_date', isset($p) ? $p->start_date?->format('Y-m-d') : '');
@endphp

<x-settings.section title="Plan Details" icon="event_repeat">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Plan Name" name="name" required>
            <x-settings.input name="name" maxlength="255" required value="{{ old('name', $p->name ?? '') }}" placeholder="Monthly Installments"/>
        </x-settings.field>
        <x-settings.field label="Fee Structure" name="fee_structure_id">
            <x-settings.select name="fee_structure_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($structures as $structure)
                    <option value="{{ $structure->id }}" @selected((int) old('fee_structure_id', $p->fee_structure_id ?? 0) === $structure->id)>{{ $structure->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Schedule Type" name="schedule_type" required>
            <x-settings.select name="schedule_type" required>
                @foreach (FeePlanController::SCHEDULE_TYPES as $type)
                    <option value="{{ $type }}" @selected(old('schedule_type', $p->schedule_type ?? '') === $type)>{{ Str::headline($type) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Number of Payments" name="number_of_payments">
            <x-settings.input type="number" min="1" name="number_of_payments" value="{{ old('number_of_payments', $p->number_of_payments ?? '') }}" placeholder="12"/>
        </x-settings.field>
        <x-settings.field label="Start Date" name="start_date">
            <x-settings.input type="date" name="start_date" value="{{ $start }}"/>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (FeePlanController::STATUSES as $st)
                    <option value="{{ $st }}" @selected(old('status', $p->status ?? 'active') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
    </div>
</x-settings.section>
