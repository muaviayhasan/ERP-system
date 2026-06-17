@props([
    'title',
    'subtitle' => null,
    'action',
    'method' => 'PUT',
    'saveLabel' => 'Save Changes',
])

{{-- Standard settings page shell: a form wrapping a header (title + save) and stacked sections. --}}
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" {{ $attributes->merge(['class' => 'pb-6']) }}>
    @csrf
    @method($method)

    <div class="mb-lg flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">{{ $title }}</h2>
            @if ($subtitle)
                <p class="text-body-md text-on-surface-variant">{{ $subtitle }}</p>
            @endif
        </div>
        <div class="flex flex-wrap items-center gap-3">
            {{ $actions ?? '' }}
            <button type="submit"
                    class="flex items-center gap-2 rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">save</span>
                {{ $saveLabel }}
            </button>
        </div>
    </div>

    <div class="space-y-lg">
        {{ $slot }}
    </div>

    <div class="mt-lg flex justify-end border-t border-outline-variant pt-lg">
        <button type="submit"
                class="rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
            {{ $saveLabel }}
        </button>
    </div>
</form>
