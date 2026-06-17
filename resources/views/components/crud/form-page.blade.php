@props([
    'title',
    'subtitle' => null,
    'back',
    'action',
    'method' => 'POST',
    'submitLabel' => 'Save',
])

{{-- Standard create/edit shell: back header + form wrapper + sticky footer actions. --}}
<div class="mb-lg flex items-center gap-3">
    <a href="{{ $back }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <div>
        <h2 class="font-headline-lg text-headline-lg text-on-surface">{{ $title }}</h2>
        @if ($subtitle)
            <p class="text-body-md text-on-surface-variant">{{ $subtitle }}</p>
        @endif
    </div>
</div>

<form method="POST" action="{{ $action }}" {{ $attributes->merge(['class' => 'max-w-3xl']) }}>
    @csrf
    @if (strtoupper($method) !== 'POST')
        @method($method)
    @endif

    <div class="space-y-lg">
        {{ $slot }}
    </div>

    <div class="mt-lg flex items-center justify-end gap-3">
        <a href="{{ $back }}" class="rounded-lg border border-outline-variant px-lg py-2.5 font-bold text-on-surface-variant transition-colors hover:bg-surface-container-low">Cancel</a>
        <button type="submit" class="rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
            {{ $submitLabel }}
        </button>
    </div>
</form>
