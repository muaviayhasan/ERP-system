@props([
    'title',
    'icon' => null,
    'desc' => null,
])

<section {{ $attributes->merge(['class' => 'rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm']) }}>
    <div class="flex items-center justify-between gap-3 rounded-t-xl border-b border-outline-variant bg-surface-container-low/40 px-6 py-4">
        <div class="flex items-center gap-2">
            @if ($icon)
                <span class="material-symbols-outlined text-primary">{{ $icon }}</span>
            @endif
            <div>
                <h3 class="font-headline-md text-headline-md text-on-surface">{{ $title }}</h3>
                @if ($desc)
                    <p class="text-label-sm text-on-surface-variant">{{ $desc }}</p>
                @endif
            </div>
        </div>
        {{ $header ?? '' }}
    </div>
    <div class="p-6">
        {{ $slot }}
    </div>
</section>
