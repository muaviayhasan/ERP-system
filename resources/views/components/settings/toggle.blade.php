@props([
    'name',
    'label' => null,
    'desc' => null,
    'checked' => false,
    'value' => 1,
])

{{-- Pure-CSS toggle bound to a checkbox; submit absence = false (use $request->boolean()). --}}
<label class="flex cursor-pointer items-center justify-between gap-4">
    <span class="min-w-0">
        @if ($label)
            <span class="block text-body-md font-medium text-on-surface">{{ $label }}</span>
        @endif
        @if ($desc)
            <span class="block text-label-sm text-on-surface-variant">{{ $desc }}</span>
        @endif
        {{ $slot }}
    </span>
    <span class="relative inline-flex h-6 w-11 shrink-0">
        <input type="checkbox" name="{{ $name }}" value="{{ $value }}" @checked($checked) class="peer sr-only">
        <span class="absolute inset-0 rounded-full bg-outline-variant transition-colors peer-checked:bg-primary"></span>
        <span class="pointer-events-none absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
    </span>
</label>
