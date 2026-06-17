@php
    use App\Http\Controllers\Admin\AcademicYearController;

    $inputClass = 'w-full rounded-lg border border-outline-variant bg-white px-md py-2.5 text-body-md outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20';
    $y = $academicYear ?? null;
    $selectedScope = old('scope', $y->scope ?? 'all_campuses');
    $selectedCampuses = array_map('strval', old('campuses', isset($y) ? $y->campuses->pluck('id')->all() : []));
    $startDate = old('start_date', isset($y) ? $y->start_date?->format('Y-m-d') : '');
    $endDate = old('end_date', isset($y) ? $y->end_date?->format('Y-m-d') : '');
@endphp

<div class="space-y-lg" x-data="{ scope: '{{ $selectedScope }}' }">
    {{-- Basic Information --}}
    <section class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
        <h3 class="mb-lg text-label-md font-bold uppercase tracking-widest text-primary">Basic Information</h3>
        <div class="grid grid-cols-1 gap-md md:grid-cols-2">
            <div class="space-y-1 md:col-span-2">
                <label class="text-label-sm font-bold text-on-surface-variant">Cycle Name <span class="text-error">*</span></label>
                <input type="text" name="name" value="{{ old('name', $y->name ?? '') }}" maxlength="255" required
                       placeholder="e.g. 2026-2027" class="{{ $inputClass }}"/>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Start Date <span class="text-error">*</span></label>
                <input type="date" name="start_date" value="{{ $startDate }}" required class="{{ $inputClass }}"/>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">End Date <span class="text-error">*</span></label>
                <input type="date" name="end_date" value="{{ $endDate }}" required class="{{ $inputClass }}"/>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Status</label>
                <select name="status" class="{{ $inputClass }}">
                    @foreach (AcademicYearController::STATUSES as $st)
                        <option value="{{ $st }}" @selected(old('status', $y->status ?? 'upcoming') === $st)>{{ ucfirst($st) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>

    {{-- Scope & Campus --}}
    <section class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
        <h3 class="mb-lg text-label-md font-bold uppercase tracking-widest text-primary">Scope &amp; Campus</h3>
        <div class="flex flex-col gap-3">
            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-outline-variant p-3 transition-colors hover:bg-surface-container-low">
                <input type="radio" name="scope" value="all_campuses" x-model="scope" class="text-primary focus:ring-primary"/>
                <div>
                    <p class="text-body-md font-bold">All Campuses</p>
                    <p class="text-label-sm text-on-surface-variant">Apply this cycle across the entire institution network.</p>
                </div>
            </label>
            <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-outline-variant p-3 transition-colors hover:bg-surface-container-low">
                <input type="radio" name="scope" value="specific_campuses" x-model="scope" class="text-primary focus:ring-primary"/>
                <div>
                    <p class="text-body-md font-bold">Specific Campuses</p>
                    <p class="text-label-sm text-on-surface-variant">Select individual branches for specialized sessions.</p>
                </div>
            </label>

            <div x-show="scope === 'specific_campuses'" x-cloak class="space-y-1 pt-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Campuses</label>
                <select name="campuses[]" multiple data-select2-parent placeholder="Select campuses..." class="{{ $inputClass }}">
                    @foreach ($campuses as $campus)
                        <option value="{{ $campus->id }}" @selected(in_array((string) $campus->id, $selectedCampuses, true))>{{ $campus->name }}</option>
                    @endforeach
                </select>
                @if ($campuses->isEmpty())
                    <p class="text-label-sm text-on-surface-variant">No campuses yet — <a href="{{ route('campuses.create') }}" class="text-primary hover:underline">add one first</a>.</p>
                @endif
            </div>
        </div>
    </section>

    {{-- Operational Linking --}}
    <section class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
        <h3 class="mb-lg text-label-md font-bold uppercase tracking-widest text-primary">Operational Linking</h3>
        <div class="space-y-3">
            <div class="rounded-xl bg-surface-container-low p-3">
                <x-settings.toggle name="link_fee_structure" label="Link Fee Structure"
                    desc="Attach the institute fee structure to this cycle."
                    :checked="old('link_fee_structure', $y->link_fee_structure ?? true)"/>
            </div>
            <div class="rounded-xl bg-surface-container-low p-3">
                <x-settings.toggle name="auto_roll_attendance" label="Auto-roll Attendance"
                    desc="Carry forward attendance configuration automatically."
                    :checked="old('auto_roll_attendance', $y->auto_roll_attendance ?? false)"/>
            </div>
        </div>

        <h4 class="mb-3 mt-lg text-label-sm font-bold uppercase tracking-wider text-on-surface-variant">Configured Modules</h4>
        <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
            <div class="rounded-xl border border-outline-variant p-3">
                <x-settings.toggle name="fees_configured" label="Fees" :checked="old('fees_configured', $y->fees_configured ?? false)"/>
            </div>
            <div class="rounded-xl border border-outline-variant p-3">
                <x-settings.toggle name="exams_configured" label="Exams" :checked="old('exams_configured', $y->exams_configured ?? false)"/>
            </div>
            <div class="rounded-xl border border-outline-variant p-3">
                <x-settings.toggle name="attendance_enabled" label="Attendance" :checked="old('attendance_enabled', $y->attendance_enabled ?? false)"/>
            </div>
        </div>
    </section>

    {{-- Business Rules --}}
    <section class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
        <h3 class="mb-lg text-label-md font-bold uppercase tracking-widest text-primary">Business Rules</h3>
        <div class="rounded-xl border border-error/10 bg-error-container/20 p-3">
            <x-settings.toggle name="prevent_date_overlap" label="Prevent Date Overlap"
                desc="Block saving when these dates overlap another cycle on the same campus."
                :checked="old('prevent_date_overlap', $y->prevent_date_overlap ?? true)"/>
        </div>
    </section>
</div>

<div class="mt-lg flex items-center justify-end gap-3">
    <a href="{{ route('academic-years.index') }}" class="rounded-lg border border-outline-variant px-lg py-2.5 font-bold text-on-surface-variant transition-colors hover:bg-surface-container-low">Cancel</a>
    <button type="submit" class="rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
        {{ isset($academicYear) ? 'Update Academic Year' : 'Save Academic Year' }}
    </button>
</div>
