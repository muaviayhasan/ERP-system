@props([
    'label' => null,
    'name' => null,
    'hint' => null,
    'required' => false,
])

<div {{ $attributes->merge(['class' => 'space-y-1']) }}>
    @if ($label)
        <label @if ($name) for="{{ $name }}" @endif class="block text-label-sm font-bold text-on-surface-variant">
            {{ $label }} @if ($required)<span class="text-error">*</span>@endif
        </label>
    @endif

    {{ $slot }}

    @if ($hint)
        <p class="text-label-sm text-on-surface-variant">{{ $hint }}</p>
    @endif
    @if ($name)
        @error($name)<p class="text-label-sm text-error">{{ $message }}</p>@enderror
    @endif
</div>
