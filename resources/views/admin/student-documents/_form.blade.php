@php
    use App\Http\Controllers\Admin\StudentDocumentController;
    $doc = $document ?? null;
    $studentId = old('student_id', $doc->student_id ?? ($selectedStudent ?? ''));
    $issue = old('issue_date', isset($doc) ? $doc->issue_date?->format('Y-m-d') : '');
    $expiry = old('expiry_date', isset($doc) ? $doc->expiry_date?->format('Y-m-d') : '');
@endphp

<div class="grid grid-cols-1 gap-lg lg:grid-cols-3">
    <div class="space-y-lg lg:col-span-2">
        <x-settings.section title="Document Details" icon="description">
            <div class="grid grid-cols-1 gap-md md:grid-cols-2">
                <x-settings.field label="Student" name="student_id" required>
                    <x-settings.select name="student_id" data-allow-clear placeholder="Select a student..." required>
                        <option value="">Select a student...</option>
                        @foreach ($students as $s)
                            <option value="{{ $s->id }}" @selected((int) $studentId === $s->id)>{{ $s->full_name }} ({{ $s->student_code }})</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Document Type" name="document_type" required>
                    <x-settings.select name="document_type" required>
                        @foreach (StudentDocumentController::TYPES as $t)
                            <option value="{{ $t }}" @selected(old('document_type', $doc->document_type ?? '') === $t)>{{ $t }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Title" name="title" required class="md:col-span-2">
                    <x-settings.input name="title" maxlength="255" required value="{{ old('title', $doc->title ?? '') }}" placeholder="ID_Card_Front.pdf"/>
                </x-settings.field>
                <x-settings.field label="Issue Date" name="issue_date">
                    <x-settings.input type="date" name="issue_date" value="{{ $issue }}"/>
                </x-settings.field>
                <x-settings.field label="Expiry Date" name="expiry_date">
                    <x-settings.input type="date" name="expiry_date" value="{{ $expiry }}"/>
                </x-settings.field>
                <x-settings.field label="File (PDF / image, max 5MB)" name="file" class="md:col-span-2">
                    @if ($doc?->file_path)
                        <p class="mb-2 text-label-sm text-on-surface-variant">
                            Current: <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-primary hover:underline">{{ $doc->title }}</a> — upload to replace.
                        </p>
                    @endif
                    <input type="file" name="file" accept=".pdf,image/*"
                           class="block w-full text-label-sm text-on-surface-variant file:mr-3 file:cursor-pointer file:rounded-lg file:border-0 file:bg-primary file:px-4 file:py-2 file:font-bold file:text-on-primary hover:file:opacity-90"/>
                </x-settings.field>
            </div>
        </x-settings.section>
    </div>

    {{-- Verification panel --}}
    <aside>
        <x-settings.section title="Verification" icon="verified_user">
            <div class="space-y-md">
                <x-settings.field label="Status" name="status">
                    <x-settings.select name="status">
                        @foreach (StudentDocumentController::STATUSES as $st)
                            <option value="{{ $st }}" @selected(old('status', $doc->status ?? 'pending') === $st)>{{ ucfirst($st) }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Verification Notes" name="verification_notes">
                    <x-settings.textarea name="verification_notes" rows="4" placeholder="Add comments regarding verification...">{{ old('verification_notes', $doc->verification_notes ?? '') }}</x-settings.textarea>
                </x-settings.field>
                @if ($doc?->verified_at)
                    <p class="rounded-lg bg-tertiary/10 px-3 py-2 text-label-sm text-tertiary">
                        Verified by {{ $doc->verifiedBy?->name ?? 'staff' }} on {{ format_datetime($doc->verified_at) }}.
                    </p>
                @endif
            </div>
        </x-settings.section>
    </aside>
</div>

<div class="mt-lg flex items-center justify-end gap-3">
    <a href="{{ route('student-documents.index') }}" class="rounded-lg border border-outline-variant px-lg py-2.5 font-bold text-on-surface-variant transition-colors hover:bg-surface-container-low">Cancel</a>
    <button type="submit" class="rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
        {{ isset($document) ? 'Save Changes' : 'Upload Document' }}
    </button>
</div>
