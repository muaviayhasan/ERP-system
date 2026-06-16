<div class="inline-flex items-center gap-3 rounded-lg border border-outline-variant bg-surface-container-low p-1">
    <button type="button" wire:click="decrement"
            class="flex h-9 w-9 items-center justify-center rounded-lg text-on-surface-variant transition-colors hover:bg-surface-container-high hover:text-primary">
        <span class="material-symbols-outlined">remove</span>
    </button>
    <span class="min-w-[2.5rem] text-center font-display-lg text-headline-md font-bold text-on-surface" wire:loading.class="opacity-40">{{ $count }}</span>
    <button type="button" wire:click="increment"
            class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary text-on-primary transition-colors hover:bg-opacity-90">
        <span class="material-symbols-outlined">add</span>
    </button>
</div>
