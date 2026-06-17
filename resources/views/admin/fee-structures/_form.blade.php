@php
    use App\Http\Controllers\Admin\FeeStructureController;
    $s = $structure ?? null;
    $fk = fn ($field, $model) => (int) old($field, $s->{$field} ?? 0) === $model->id;
    $frequencies = ['One-time', 'Monthly', 'Quarterly', 'Semester', 'Annual'];
    $initialRows = old('components', isset($s) && $s->feeStructureComponents->count()
        ? $s->feeStructureComponents->map(fn ($c) => ['name' => $c->name, 'fee_category_id' => $c->fee_category_id, 'amount' => $c->amount, 'frequency' => $c->frequency, 'taxable' => (bool) $c->taxable])->all()
        : [['name' => '', 'fee_category_id' => '', 'amount' => '', 'frequency' => 'One-time', 'taxable' => false]]);
@endphp

<x-settings.section title="Structure Details" icon="receipt_long">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Name" name="name" required>
            <x-settings.input name="name" maxlength="255" required value="{{ old('name', $s->name ?? '') }}" placeholder="BSCS Semester Fee Plan 2026"/>
        </x-settings.field>
        <x-settings.field label="Code" name="code" required>
            <x-settings.input name="code" maxlength="255" required value="{{ old('code', $s->code ?? '') }}" placeholder="EDU-FE-092-B"/>
        </x-settings.field>
        <x-settings.field label="Campus" name="campus_id">
            <x-settings.select name="campus_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($campuses as $campus)<option value="{{ $campus->id }}" @selected($fk('campus_id', $campus))>{{ $campus->name }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Program" name="program_id">
            <x-settings.select name="program_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($programs as $program)<option value="{{ $program->id }}" @selected($fk('program_id', $program))>{{ $program->name }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Academic Year" name="academic_year_id">
            <x-settings.select name="academic_year_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($academicYears as $year)<option value="{{ $year->id }}" @selected($fk('academic_year_id', $year))>{{ $year->name }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Level" name="level">
            <x-settings.input name="level" maxlength="255" value="{{ old('level', $s->level ?? '') }}" placeholder="Undergraduate"/>
        </x-settings.field>
        <x-settings.field label="Billing Cycle" name="billing_cycle" required>
            <x-settings.select name="billing_cycle" required>
                @foreach (FeeStructureController::BILLING_CYCLES as $cycle)<option value="{{ $cycle }}" @selected(old('billing_cycle', $s->billing_cycle ?? '') === $cycle)>{{ $cycle }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (FeeStructureController::STATUSES as $st)<option value="{{ $st }}" @selected(old('status', $s->status ?? 'draft') === $st)>{{ ucfirst($st) }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="scholarship_available" label="Scholarship Available" :checked="old('scholarship_available', $s->scholarship_available ?? false)"/>
        </div>
        <div class="rounded-lg border border-outline-variant p-4">
            <x-settings.toggle name="installments_enabled" label="Installments Enabled" :checked="old('installments_enabled', $s->installments_enabled ?? false)"/>
        </div>
        <x-settings.field label="Installment Count" name="installment_count">
            <x-settings.input type="number" min="0" name="installment_count" value="{{ old('installment_count', $s->installment_count ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Billing Day of Month" name="billing_day_of_month">
            <x-settings.input type="number" min="1" max="31" name="billing_day_of_month" value="{{ old('billing_day_of_month', $s->billing_day_of_month ?? '') }}"/>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Fee Components" icon="list" desc="Line items that make up this structure — the total fee is their sum.">
    <div x-data="{
            rows: @js(array_values($initialRows)),
            freqs: @js($frequencies),
            cats: @js($categories->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->all()),
            add() { this.rows.push({ name: '', fee_category_id: '', amount: '', frequency: 'One-time', taxable: false }) },
            remove(i) { this.rows.splice(i, 1) },
            get total() { return this.rows.reduce((s, r) => s + (parseFloat(r.amount) || 0), 0) },
        }">
        <div class="space-y-3">
            <template x-for="(row, i) in rows" :key="i">
                <div class="grid grid-cols-1 items-end gap-2 rounded-lg border border-outline-variant p-3 md:grid-cols-12">
                    <div class="md:col-span-4">
                        <label class="text-label-sm font-bold text-on-surface-variant">Component</label>
                        <input type="text" x-model="row.name" :name="`components[${i}][name]`" placeholder="Tuition Fee"
                               class="w-full rounded-lg border border-outline-variant bg-white px-3 py-2 text-body-md outline-none focus:border-primary"/>
                    </div>
                    <div class="md:col-span-3">
                        <label class="text-label-sm font-bold text-on-surface-variant">Category</label>
                        <select x-model="row.fee_category_id" :name="`components[${i}][fee_category_id]`"
                                class="w-full rounded-lg border border-outline-variant bg-white px-3 py-2 text-body-md outline-none focus:border-primary">
                            <option value="">—</option>
                            <template x-for="cat in cats" :key="cat.id"><option :value="cat.id" x-text="cat.name"></option></template>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-label-sm font-bold text-on-surface-variant">Amount</label>
                        <input type="number" step="0.01" min="0" x-model="row.amount" :name="`components[${i}][amount]`"
                               class="w-full rounded-lg border border-outline-variant bg-white px-3 py-2 text-body-md outline-none focus:border-primary"/>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-label-sm font-bold text-on-surface-variant">Frequency</label>
                        <select x-model="row.frequency" :name="`components[${i}][frequency]`"
                                class="w-full rounded-lg border border-outline-variant bg-white px-3 py-2 text-body-md outline-none focus:border-primary">
                            <template x-for="f in freqs" :key="f"><option :value="f" x-text="f"></option></template>
                        </select>
                    </div>
                    <div class="flex items-center justify-between gap-2 md:col-span-1">
                        <label class="flex items-center gap-1 text-label-sm text-on-surface-variant">
                            <input type="checkbox" x-model="row.taxable" :name="`components[${i}][taxable]`" value="1"/> Tax
                        </label>
                        <button type="button" @click="remove(i)" class="rounded p-1 text-on-surface-variant hover:text-error"><span class="material-symbols-outlined text-[18px]">delete</span></button>
                    </div>
                </div>
            </template>
        </div>
        <div class="mt-3 flex items-center justify-between">
            <button type="button" @click="add()" class="flex items-center gap-1 rounded-lg border border-outline-variant px-4 py-2 text-label-md font-bold text-primary hover:bg-surface-container-low">
                <span class="material-symbols-outlined text-[18px]">add</span> Add Component
            </button>
            <p class="text-body-md font-bold text-on-surface">Total: <span x-text="new Intl.NumberFormat().format(total)"></span></p>
        </div>
    </div>
</x-settings.section>
