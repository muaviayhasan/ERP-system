@props([
    'name',                 // form field name (required)
    'label' => null,        // optional field label
    'multiple' => false,    // allow multiple files
    'accept' => '',         // e.g. "image/*,.pdf"
    'maxMb' => 5,           // max size per file (MB)
    'hint' => null,         // helper text under the dropzone
    'id' => null,
])

@php
    $fieldId = $id ?? $name;
    $inputName = $multiple ? $name.'[]' : $name;
@endphp

<div
    x-data="fileDrop({ multiple: {{ $multiple ? 'true' : 'false' }}, maxMb: {{ (float) $maxMb }}, accept: @js($accept) })"
    {{ $attributes->merge(['class' => 'space-y-2']) }}
>
    @if ($label)
        <label for="{{ $fieldId }}" class="text-label-sm font-bold text-on-surface-variant">{{ $label }}</label>
    @endif

    {{-- Drop zone --}}
    <div
        role="button"
        tabindex="0"
        @click="$refs.input.click()"
        @keydown.enter.prevent="$refs.input.click()"
        @dragover.prevent="dragging = true"
        @dragenter.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="handleDrop($event)"
        class="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed px-md py-8 text-center transition-colors"
        :class="dragging
            ? 'border-primary bg-primary/5'
            : 'border-outline-variant bg-surface-container-low hover:border-primary/50 hover:bg-surface-container'"
    >
        <span class="material-symbols-outlined text-[36px] text-primary" :class="dragging && 'animate-bounce'">cloud_upload</span>
        <p class="text-body-md text-on-surface">
            <span class="font-bold text-primary">Click to upload</span> or drag and drop
        </p>
        <p class="text-label-sm text-on-surface-variant">
            {{ $hint ?? trim(($accept ? strtoupper(str_replace(['image/*', '.', ','], ['images', '', ', '], $accept)).' · ' : '').'up to '.$maxMb.' MB'.($multiple ? ' each' : ''), ' ·') }}
        </p>

        <input
            type="file"
            id="{{ $fieldId }}"
            name="{{ $inputName }}"
            x-ref="input"
            class="hidden"
            @if ($accept) accept="{{ $accept }}" @endif
            @if ($multiple) multiple @endif
            @change="handleChange($event)"
        />
    </div>

    {{-- Validation message --}}
    <p x-show="error" x-cloak x-text="error" class="flex items-center gap-1 text-label-sm text-error"></p>

    {{-- Selected files --}}
    <ul x-show="files.length" x-cloak class="space-y-2">
        <template x-for="entry in files" :key="entry.id">
            <li class="flex items-center gap-md rounded-lg border border-outline-variant bg-surface-container-lowest px-md py-2">
                <template x-if="entry.url">
                    <img :src="entry.url" class="h-10 w-10 rounded object-cover" alt=""/>
                </template>
                <template x-if="!entry.url">
                    <span class="material-symbols-outlined flex h-10 w-10 items-center justify-center rounded bg-surface-container-high text-on-surface-variant">description</span>
                </template>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-label-md font-medium text-on-surface" x-text="entry.file.name"></p>
                    <p class="text-label-sm text-on-surface-variant" x-text="formatSize(entry.file.size)"></p>
                </div>
                <button type="button" @click="remove(entry.id)" class="rounded-lg p-1.5 text-on-surface-variant transition-colors hover:bg-error-container/40 hover:text-error">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </li>
        </template>
    </ul>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('fileDrop', (opts) => ({
                    multiple: opts.multiple,
                    maxBytes: opts.maxMb * 1024 * 1024,
                    accept: opts.accept,
                    dragging: false,
                    files: [],
                    error: '',
                    nextId: 0,

                    handleDrop(e) {
                        this.dragging = false;
                        this.addFiles(e.dataTransfer.files);
                    },

                    handleChange(e) {
                        this.addFiles(e.target.files);
                    },

                    addFiles(list) {
                        this.error = '';
                        if (!this.multiple) this.clear();
                        for (const file of Array.from(list)) {
                            if (this.maxBytes && file.size > this.maxBytes) {
                                this.error = `"${file.name}" exceeds ${opts.maxMb} MB.`;
                                continue;
                            }
                            if (!this.accepts(file)) {
                                this.error = `"${file.name}" is not an allowed file type.`;
                                continue;
                            }
                            this.files.push({
                                file,
                                id: this.nextId++,
                                url: file.type.startsWith('image/') ? URL.createObjectURL(file) : null,
                            });
                            if (!this.multiple) break;
                        }
                        this.sync();
                    },

                    accepts(file) {
                        if (!this.accept) return true;
                        const name = file.name.toLowerCase();
                        const type = (file.type || '').toLowerCase();
                        return this.accept.split(',').map((p) => p.trim().toLowerCase()).some((p) => {
                            if (!p) return false;
                            if (p.endsWith('/*')) return type.startsWith(p.slice(0, -1));
                            if (p.startsWith('.')) return name.endsWith(p);
                            return type === p;
                        });
                    },

                    remove(id) {
                        const entry = this.files.find((f) => f.id === id);
                        if (entry && entry.url) URL.revokeObjectURL(entry.url);
                        this.files = this.files.filter((f) => f.id !== id);
                        this.sync();
                    },

                    clear() {
                        this.files.forEach((f) => f.url && URL.revokeObjectURL(f.url));
                        this.files = [];
                    },

                    // Push the selected files back into the real <input> so they submit with the form.
                    sync() {
                        const dt = new DataTransfer();
                        this.files.forEach((f) => dt.items.add(f.file));
                        this.$refs.input.files = dt.files;
                    },

                    formatSize(bytes) {
                        if (bytes < 1024) return bytes + ' B';
                        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
                        return (bytes / 1048576).toFixed(1) + ' MB';
                    },
                }));
            });
        </script>
    @endpush
@endonce
