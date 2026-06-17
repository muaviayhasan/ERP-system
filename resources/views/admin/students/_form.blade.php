@php
    use App\Http\Controllers\Admin\StudentController;
    $st = $student ?? null;
    $inputClass = 'w-full rounded-lg border border-outline-variant bg-white px-md py-2.5 text-body-md outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20';
    $val = fn ($field, $default = '') => old($field, $st->{$field} ?? $default);
    $sel = fn ($field, $option, $default = null) => (string) old($field, $st->{$field} ?? $default) === (string) $option;
    $dob = old('date_of_birth', isset($st) ? $st->date_of_birth?->format('Y-m-d') : '');
    $steps = ['Personal', 'Contact', 'Academic', 'Review'];
@endphp

<div x-data="{
        step: 1,
        total: {{ count($steps) }},
        firstName: @js(old('first_name', $st->first_name ?? '')),
        lastName: @js(old('last_name', $st->last_name ?? '')),
        code: @js(old('student_code', $st->student_code ?? '')),
        next() { if (this.step < this.total) this.step++; },
        prev() { if (this.step > 1) this.step--; },
     }"
     class="grid grid-cols-1 gap-lg lg:grid-cols-3">

    {{-- Wizard card --}}
    <div class="lg:col-span-2">
        {{-- Step indicator --}}
        <ol class="mb-lg flex items-center gap-2">
            @foreach ($steps as $i => $label)
                @php $n = $i + 1; @endphp
                <li class="flex flex-1 items-center gap-2">
                    <button type="button" @click="step = {{ $n }}"
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-label-md font-bold transition-colors"
                            :class="step >= {{ $n }} ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface-variant'">{{ $n }}</button>
                    <span class="hidden text-label-md font-medium sm:block" :class="step >= {{ $n }} ? 'text-primary' : 'text-on-surface-variant'">{{ $label }}</span>
                    @if (! $loop->last)<span class="h-px flex-1 bg-outline-variant"></span>@endif
                </li>
            @endforeach
        </ol>

        <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
            {{-- Step 1: Personal --}}
            <div x-show="step === 1" class="space-y-md">
                <h3 class="text-label-md font-bold uppercase tracking-widest text-primary">Personal Information</h3>
                <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">First Name <span class="text-error">*</span></label>
                        <input type="text" name="first_name" x-model="firstName" maxlength="255" required class="{{ $inputClass }}"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Last Name</label>
                        <input type="text" name="last_name" x-model="lastName" maxlength="255" class="{{ $inputClass }}"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Father's Name</label>
                        <input type="text" name="father_name" value="{{ $val('father_name') }}" maxlength="255" class="{{ $inputClass }}"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Date of Birth</label>
                        <input type="date" name="date_of_birth" value="{{ $dob }}" class="{{ $inputClass }}"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Gender</label>
                        <select name="gender" data-allow-clear placeholder="Select..." class="{{ $inputClass }}">
                            <option value="">Select...</option>
                            @foreach (StudentController::GENDERS as $g)
                                <option value="{{ $g }}" @selected($sel('gender', $g))>{{ ucfirst($g) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">CNIC / B-Form</label>
                        <input type="text" name="cnic" data-mask="cnic" maxlength="15" value="{{ $val('cnic') }}" placeholder="32301-0000000-0" class="{{ $inputClass }}"/>
                    </div>
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-label-sm font-bold text-on-surface-variant">Photo</label>
                        @if ($st?->photo_url)
                            <div class="mb-2 flex items-center gap-3">
                                <img src="{{ Storage::url($st->photo_url) }}" alt="" class="h-12 w-12 rounded-full object-cover"/>
                                <span class="text-label-sm text-on-surface-variant">Current photo — upload to replace.</span>
                            </div>
                        @endif
                        <input type="file" name="photo" accept="image/*"
                               class="block w-full text-label-sm text-on-surface-variant file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:font-bold file:text-on-primary hover:file:opacity-90"/>
                    </div>
                </div>
            </div>

            {{-- Step 2: Contact & Identity --}}
            <div x-show="step === 2" x-cloak class="space-y-md">
                <h3 class="text-label-md font-bold uppercase tracking-widest text-primary">Contact &amp; Identity</h3>
                <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Email</label>
                        <input type="email" name="email" value="{{ $val('email') }}" maxlength="255" class="{{ $inputClass }}"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Phone</label>
                        <input type="text" name="phone" data-mask="phone" maxlength="12" value="{{ $val('phone') }}" placeholder="0300-0000000" class="{{ $inputClass }}"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Student Code <span class="text-error">*</span></label>
                        <input type="text" name="student_code" x-model="code" maxlength="255" required placeholder="STU-2024-001" class="{{ $inputClass }}"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Roll Number</label>
                        <input type="number" name="roll_number" value="{{ $val('roll_number') }}" class="{{ $inputClass }}"/>
                    </div>
                    <div class="space-y-1 md:col-span-2">
                        <label class="text-label-sm font-bold text-on-surface-variant">Enrollment Session</label>
                        <input type="text" name="enrollment_session" value="{{ $val('enrollment_session') }}" placeholder="Fall 2024" class="{{ $inputClass }}"/>
                    </div>
                </div>
            </div>

            {{-- Step 3: Academic Enrollment --}}
            <div x-show="step === 3" x-cloak class="space-y-md">
                <h3 class="text-label-md font-bold uppercase tracking-widest text-primary">Academic Enrollment</h3>
                <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Institute Type</label>
                        <select name="institute_type" data-allow-clear placeholder="Select..." class="{{ $inputClass }}">
                            <option value="">Select...</option>
                            @foreach (StudentController::INSTITUTE_TYPES as $t)
                                <option value="{{ $t }}" @selected($sel('institute_type', $t))>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Campus</label>
                        <select name="campus_id" data-allow-clear placeholder="Select..." class="{{ $inputClass }}">
                            <option value="">Unassigned</option>
                            @foreach ($campuses as $campus)
                                <option value="{{ $campus->id }}" @selected($sel('campus_id', $campus->id))>{{ $campus->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Program</label>
                        <select name="program_id" data-allow-clear placeholder="Select..." class="{{ $inputClass }}">
                            <option value="">Unassigned</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}" @selected($sel('program_id', $program->id))>{{ $program->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Academic Year</label>
                        <select name="academic_year_id" data-allow-clear placeholder="Select..." class="{{ $inputClass }}">
                            <option value="">Unassigned</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}" @selected($sel('academic_year_id', $year->id))>{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Current Semester</label>
                        <select name="current_semester_id" data-allow-clear placeholder="Select..." class="{{ $inputClass }}">
                            <option value="">Unassigned</option>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}" @selected($sel('current_semester_id', $semester->id))>{{ $semester->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Section</label>
                        <select name="section_id" data-allow-clear placeholder="Select..." class="{{ $inputClass }}">
                            <option value="">Unassigned</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}" @selected($sel('section_id', $section->id))>{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Batch</label>
                        <select name="batch_id" data-allow-clear placeholder="Select..." class="{{ $inputClass }}">
                            <option value="">Unassigned</option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->id }}" @selected($sel('batch_id', $batch->id))>{{ $batch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Specialization</label>
                        <input type="text" name="specialization" value="{{ $val('specialization') }}" maxlength="255" class="{{ $inputClass }}"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Credit Hours</label>
                        <input type="number" name="current_credit_hours" value="{{ $val('current_credit_hours') }}" min="0" class="{{ $inputClass }}"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Scholarship Type</label>
                        <input type="text" name="scholarship_type" value="{{ $val('scholarship_type') }}" maxlength="255" placeholder="Merit / Need-based" class="{{ $inputClass }}"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Status</label>
                        <select name="status" class="{{ $inputClass }}">
                            @foreach (StudentController::STATUSES as $s)
                                <option value="{{ $s }}" @selected($sel('status', $s, 'active'))>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Admission Status</label>
                        <select name="admission_status" class="{{ $inputClass }}">
                            @foreach (StudentController::ADMISSION_STATUSES as $s)
                                <option value="{{ $s }}" @selected($sel('admission_status', $s, 'enrolled'))>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Step 4: Review --}}
            <div x-show="step === 4" x-cloak class="space-y-md">
                <h3 class="text-label-md font-bold uppercase tracking-widest text-primary">Review &amp; Submit</h3>
                <div class="rounded-lg border border-outline-variant bg-surface-container-low p-md">
                    <p class="text-body-md text-on-surface">You are about to {{ isset($student) ? 'update' : 'admit' }}
                        <strong x-text="(firstName + ' ' + lastName).trim() || 'this student'"></strong>
                        <span x-show="code" class="text-on-surface-variant">(<span x-text="code"></span>)</span>.
                    </p>
                    <p class="mt-1 text-label-sm text-on-surface-variant">Review the previous steps, then submit to save the record.</p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="mt-lg flex items-center justify-between border-t border-outline-variant pt-lg">
                <button type="button" @click="prev()" x-show="step > 1"
                        class="rounded-lg border border-outline-variant px-lg py-2.5 font-bold text-on-surface-variant transition-colors hover:bg-surface-container-low">
                    <span class="material-symbols-outlined align-middle text-[18px]">arrow_back</span> Previous
                </button>
                <span x-show="step === 1"></span>
                <div class="flex items-center gap-3">
                    <a href="{{ route('students.index') }}" class="rounded-lg px-lg py-2.5 font-bold text-on-surface-variant hover:bg-surface-container-low">Cancel</a>
                    <button type="button" @click="next()" x-show="step < total"
                            class="rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                        Next <span class="material-symbols-outlined align-middle text-[18px]">arrow_forward</span>
                    </button>
                    <button type="submit" x-show="step === total"
                            class="rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                        {{ isset($student) ? 'Update Student' : 'Submit Admission' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Admission summary sidebar --}}
    <aside class="space-y-lg">
        <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
            <h3 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">Admission Summary</h3>
            <div class="space-y-3 text-body-md">
                <div>
                    <p class="text-label-sm text-on-surface-variant">Student</p>
                    <p class="font-bold text-on-surface" x-text="(firstName + ' ' + lastName).trim() || '—'"></p>
                </div>
                <div>
                    <p class="text-label-sm text-on-surface-variant">Student Code</p>
                    <p class="font-bold text-on-surface" x-text="code || '—'"></p>
                </div>
                <div class="border-t border-outline-variant pt-3">
                    <div class="mb-1 flex justify-between text-label-sm">
                        <span class="text-on-surface-variant">Completion</span>
                        <span class="font-bold text-primary" x-text="Math.round(step / total * 100) + '%'"></span>
                    </div>
                    <div class="h-2 w-full overflow-hidden rounded-full bg-surface-container-high">
                        <div class="h-full bg-primary transition-all" :style="`width: ${step / total * 100}%`"></div>
                    </div>
                    <p class="mt-1 text-label-sm text-on-surface-variant">Step <span x-text="step"></span> of <span x-text="total"></span></p>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-primary/20 bg-primary/5 p-lg">
            <div class="flex items-start gap-2">
                <span class="material-symbols-outlined text-primary">lightbulb</span>
                <p class="text-label-md text-on-surface-variant">Ensure the student code is unique and the academic enrollment matches an existing program and campus.</p>
            </div>
        </div>
    </aside>
</div>
