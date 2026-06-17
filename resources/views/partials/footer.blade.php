@php
    $contactEmail = setting('general', 'contact_email');
    $contactPhone = setting('general', 'phone');
@endphp
<footer class="mt-auto border-t border-outline-variant bg-surface-container-lowest px-lg py-4">
    <div class="flex flex-col items-center justify-between gap-2 text-label-sm text-on-surface-variant sm:flex-row">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Education ERP') }}. All rights reserved.</p>
        <div class="flex flex-wrap items-center gap-md">
            @if ($contactEmail)
                <a href="mailto:{{ $contactEmail }}" class="flex items-center gap-1 transition-colors hover:text-primary">
                    <span class="material-symbols-outlined text-[16px]">mail</span>{{ $contactEmail }}
                </a>
            @endif
            @if ($contactPhone)
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">call</span>{{ $contactPhone }}</span>
            @endif
            <a href="#" class="transition-colors hover:text-primary">Privacy</a>
            <a href="#" class="transition-colors hover:text-primary">Terms</a>
            <span class="rounded-full bg-surface-container-high px-2 py-0.5 font-medium">v1.0.0</span>
        </div>
    </div>
</footer>
