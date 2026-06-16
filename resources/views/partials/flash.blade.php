@if (session('status'))
    <div x-data="{ show: true }" x-show="show" x-cloak
         class="mb-lg flex items-center justify-between gap-3 rounded-lg border border-tertiary/30 bg-tertiary/10 px-md py-3 text-body-md text-tertiary">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('status') }}</span>
        </div>
        <button @click="show = false" class="text-tertiary/70 hover:text-tertiary"><span class="material-symbols-outlined text-[18px]">close</span></button>
    </div>
@endif

@if ($errors->any())
    <div class="mb-lg rounded-lg border border-error/30 bg-error/10 px-md py-3 text-body-md text-error">
        <div class="mb-1 flex items-center gap-2 font-semibold">
            <span class="material-symbols-outlined">error</span>
            <span>Please fix the following:</span>
        </div>
        <ul class="ml-7 list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
