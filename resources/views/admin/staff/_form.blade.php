@php
    use App\Http\Controllers\Admin\StaffController;
    $m = $staff ?? null;
    $fk = fn ($field, $model) => (int) old($field, $m->{$field} ?? 0) === $model->id;
    $joining = old('joining_date', isset($m) ? $m->joining_date?->format('Y-m-d') : '');
@endphp

<x-settings.section title="Basic Information" icon="badge">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="First Name" name="first_name" required>
            <x-settings.input name="first_name" maxlength="255" required value="{{ old('first_name', $m->first_name ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Last Name" name="last_name" required>
            <x-settings.input name="last_name" maxlength="255" required value="{{ old('last_name', $m->last_name ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Staff Code" name="staff_code" required>
            <x-settings.input name="staff_code" maxlength="255" required value="{{ old('staff_code', $m->staff_code ?? '') }}" placeholder="EMP-2024-001"/>
        </x-settings.field>
        <x-settings.field label="Role" name="role" required>
            <x-settings.input name="role" maxlength="255" required list="role-list" value="{{ old('role', $m->role ?? '') }}" placeholder="IT Manager"/>
            <datalist id="role-list">
                @foreach (StaffController::ROLES as $r)<option value="{{ $r }}">@endforeach
            </datalist>
        </x-settings.field>
        <x-settings.field label="Email" name="email">
            <x-settings.input type="email" name="email" maxlength="255" value="{{ old('email', $m->email ?? '') }}"/>
        </x-settings.field>
        <x-settings.field label="Phone" name="phone">
            <x-settings.input name="phone" data-mask="phone" maxlength="12" value="{{ old('phone', $m->phone ?? '') }}" placeholder="0300-0000000"/>
        </x-settings.field>
        <x-settings.field label="Photo" name="photo" class="md:col-span-2">
            @if ($m?->photo_url)
                <div class="mb-2 flex items-center gap-3">
                    <img src="{{ Storage::url($m->photo_url) }}" alt="" class="h-12 w-12 rounded-full object-cover"/>
                    <span class="text-label-sm text-on-surface-variant">Current photo — upload to replace.</span>
                </div>
            @endif
            <input type="file" name="photo" accept="image/*"
                   class="block w-full text-label-sm text-on-surface-variant file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:font-bold file:text-on-primary hover:file:opacity-90"/>
        </x-settings.field>
    </div>
</x-settings.section>

<x-settings.section title="Employment" icon="work">
    <div class="grid grid-cols-1 gap-md md:grid-cols-2">
        <x-settings.field label="Department" name="department_id">
            <x-settings.select name="department_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @selected($fk('department_id', $department))>{{ $department->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Campus" name="campus_id">
            <x-settings.select name="campus_id" data-allow-clear placeholder="Select...">
                <option value="">Unassigned</option>
                @foreach ($campuses as $campus)
                    <option value="{{ $campus->id }}" @selected($fk('campus_id', $campus))>{{ $campus->name }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Shift" name="shift">
            <x-settings.select name="shift">
                @foreach (StaffController::SHIFTS as $shift)
                    <option value="{{ $shift }}" @selected(old('shift', $m->shift ?? 'Morning') === $shift)>{{ $shift }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Reports To" name="reporting_to_id">
            <x-settings.select name="reporting_to_id" data-allow-clear placeholder="Select...">
                <option value="">None</option>
                @foreach ($managers as $manager)
                    @if (! isset($m) || $manager->id !== $m->id)
                        <option value="{{ $manager->id }}" @selected($fk('reporting_to_id', $manager))>{{ $manager->full_name }} ({{ $manager->staff_code }})</option>
                    @endif
                @endforeach
            </x-settings.select>
        </x-settings.field>
        <x-settings.field label="Joining Date" name="joining_date">
            <x-settings.input type="date" name="joining_date" value="{{ $joining }}"/>
        </x-settings.field>
        <x-settings.field label="Status" name="status">
            <x-settings.select name="status">
                @foreach (StaffController::STATUSES as $st)
                    <option value="{{ $st }}" @selected(old('status', $m->status ?? 'active') === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </x-settings.select>
        </x-settings.field>
    </div>
</x-settings.section>
