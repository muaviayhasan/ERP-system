@php
    use App\Http\Controllers\Admin\ScholarshipController;
    $s = $scholarship ?? null;
@endphp

<x-settings.section title="Scholarship Policy" icon="volunteer_activism">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Name" name="name" required>
            <x-settings.input name="name" maxlength="255" required value="{{ old('name', $s->name ?? '') }}" placeholder="Academic Excellence 50%"/>
        </x-settings.field>
        <x-settings.field label="Code" name="code" required>
            <x-settings.input name="code" maxlength="255" required value="{{ old('code', $s->code ?? '') }}" placeholder="SCH-MER-2024-01"/>
        </x-settings.field>
        <x-settings.field label="Type" name="type" required>
            <x-settings.select name="type" required>
                @foreach (ScholarshipController::TYPES as $type)<option value="{{ $type }}" @selected(old('type', $s->type ?? '') === $type)>{{ ucfirst($type) }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Value Type" name="value_type" required>
            <x-settings.select name="value_type" required>
                @foreach (ScholarshipController::VALUE_TYPES as $vt)<option value="{{ $vt }}" @selected(old('value_type', $s->value_type ?? 'percentage') === $vt)>{{ Str::headline($vt) }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Value" name="value" required hint="A percentage (e.g. 50) or a fixed amount.">
            <x-settings.input type="number" step="0.01" min="0" name="value" required value="{{ old('value', $s->value ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Level" name="level">
            <x-settings.input name="level" maxlength="255" value="{{ old('level', $s->level ?? '') }}" placeholder="Bachelor Programs"/>
        </x-settings.field>
        <x-settings.field label="Estimated Liability" name="estimated_liability">
            <x-settings.input type="number" step="0.01" min="0" name="estimated_liability" value="{{ old('estimated_liability', $s->estimated_liability ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (ScholarshipController::STATUSES as $st)<option value="{{ $st }}" @selected(old('status', $s->status ?? 'active') === $st)>{{ ucfirst($st) }}</option>@endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Criteria" name="criteria" class="md:col-span-2">
            <x-settings.textarea name="criteria" rows="3" placeholder="Eligibility criteria and conditions...">{{ old('criteria', $s->criteria ?? '') }}</x-settings.textarea>
        </x-settings.field>
    </div>
</x-settings.section>
