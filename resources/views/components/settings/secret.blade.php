@props([
    'name',
    'isSet' => false,
])

{{-- Masked secret input. Renders empty; on save the controller keeps the stored
     value when left blank, so secrets are never echoed back to the browser. --}}
<div class="relative" x-data="{ show: false }">
    <input :type="show ? 'text' : 'password'" name="{{ $name }}" id="{{ $name }}" autocomplete="new-password"
           placeholder="{{ $isSet ? '•••••••• saved — leave blank to keep' : 'Not set' }}"
           {{ $attributes->merge(['class' => 'w-full rounded-lg border border-outline-variant bg-white px-md py-2.5 pr-10 text-body-md text-on-surface outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20']) }}>
    <button type="button" @click="show = !show" tabindex="-1"
            class="absolute right-2 top-1/2 -translate-y-1/2 rounded p-1 text-on-surface-variant hover:bg-surface-container-high">
        <span class="material-symbols-outlined text-[18px]" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
    </button>
</div>
