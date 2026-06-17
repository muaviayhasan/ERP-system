{{--
    Shared logout confirmation modal.
    Opened by dispatching the `open-logout-modal` browser event
    (e.g. @click="$dispatch('open-logout-modal')") from the sidebar / header buttons.

    On confirm we fetch a *fresh* CSRF token before submitting, so a modal that has
    been sitting open for 10+ minutes never fails with a 419 (token mismatch).
--}}
<div x-data="{ open: false, busy: false }"
     x-on:open-logout-modal.window="open = true"
     x-cloak>
    <template x-teleport="body">
        <div x-show="open" class="fixed inset-0 z-[60] flex items-center justify-center p-lg">
            {{-- Backdrop --}}
            <div x-show="open" x-transition.opacity
                 @click="if (!busy) open = false"
                 class="absolute inset-0 bg-on-surface/40 backdrop-blur-sm"></div>

            {{-- Dialog --}}
            <div x-show="open"
                 x-transition.scale.origin.center
                 @keydown.escape.window="if (!busy) open = false"
                 class="relative w-full max-w-[26rem] rounded-2xl border border-outline-variant bg-surface-container-lowest p-lg shadow-xl">
                <div class="flex items-start gap-md">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-error-container/40 text-error">
                        <span class="material-symbols-outlined">logout</span>
                    </div>
                    <div>
                        <h2 class="text-label-lg font-bold text-on-surface">Sign out?</h2>
                        <p class="mt-xs text-label-md text-on-surface-variant">
                            You’ll be returned to the login screen. Any unsaved changes may be lost.
                        </p>
                    </div>
                </div>

                <form id="logout-form" method="POST" action="{{ route('logout') }}"
                      class="mt-lg flex justify-end gap-sm">
                    @csrf
                    <button type="button" @click="open = false" :disabled="busy"
                            class="cursor-pointer rounded-lg border border-outline-variant px-lg py-sm text-label-md text-on-surface-variant transition-colors hover:bg-surface-container-high disabled:opacity-50">
                        No, stay
                    </button>
                    <button type="button" @click="busy = true; window.confirmLogout()" :disabled="busy"
                            class="cursor-pointer rounded-lg bg-error px-lg py-sm font-label-md text-white transition-all hover:opacity-90 active:scale-95 disabled:opacity-70">
                        <span x-show="!busy">Yes, sign out</span>
                        <span x-show="busy" x-cloak>Signing out…</span>
                    </button>
                </form>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
    // Refresh the CSRF token right before logging out, then submit the form.
    // This guards against a 419 when the confirmation modal has been open for a
    // long time and the page-rendered token has gone stale.
    window.confirmLogout = async function () {
        const form = document.getElementById('logout-form');
        if (!form) return;
        const tokenInput = form.querySelector('input[name="_token"]');
        try {
            const res = await fetch('{{ route('csrf.token') }}', {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
                cache: 'no-store',
            });
            if (res.ok) {
                const data = await res.json();
                if (data && data.token && tokenInput) {
                    tokenInput.value = data.token;
                }
            }
        } catch (e) {
            // Couldn't reach the token endpoint — fall back to the embedded token.
        }
        form.submit();
    };
</script>
@endpush
