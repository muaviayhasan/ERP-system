@php
    /**
     * Resolve a menu item URL. Items may declare a named 'route' or an absolute
     * 'url'; otherwise they are placeholders ('#') until their page is built.
     */
    $itemUrl = function (array $item): string {
        if (! empty($item['route']) && \Illuminate\Support\Facades\Route::has($item['route'])) {
            return route($item['route']);
        }
        return $item['url'] ?? '#';
    };

    $itemActive = function (array $item): bool {
        return ! empty($item['route']) && request()->routeIs($item['route']);
    };
@endphp

<aside
    x-cloak
    class="fixed left-0 top-0 z-50 flex h-screen w-[260px] -translate-x-full flex-col border-r border-outline-variant bg-surface-container-low px-md py-lg transition-transform duration-300 lg:translate-x-0"
    :class="sidebarOpen && 'translate-x-0'">

    {{-- Brand --}}
    <div class="mb-xl flex items-center gap-sm px-1">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary text-white">
            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">school</span>
        </div>
        <div>
            <h1 class="font-headline-lg text-headline-lg font-bold text-primary leading-tight">EduCore ERP</h1>
            <p class="text-label-sm uppercase tracking-wider text-on-surface-variant">Admin Panel</p>
        </div>
        <button @click="sidebarOpen = false" class="ml-auto rounded-lg p-1 text-on-surface-variant hover:bg-surface-container-high lg:hidden">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="custom-scrollbar flex-1 space-y-xs overflow-y-auto pr-1">
        @foreach (config('navigation') as $entry)
            @if (empty($entry['children']))
                {{-- Single link --}}
                @php $active = $itemActive($entry); @endphp
                <a href="{{ $itemUrl($entry) }}"
                   class="flex items-center gap-md rounded-lg px-md py-sm transition-colors duration-200
                          {{ $active ? 'bg-secondary-container font-bold text-primary' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                    <span class="material-symbols-outlined">{{ $entry['icon'] }}</span>
                    <span class="font-label-md text-label-md">{{ $entry['label'] }}</span>
                </a>
            @else
                {{-- Collapsible group (menu → submenu) --}}
                @php $groupActive = collect($entry['children'])->contains(fn ($c) => $itemActive($c)); @endphp
                <div x-data="{ open: {{ $groupActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="flex w-full items-center gap-md rounded-lg px-md py-sm text-on-surface-variant transition-colors duration-200 hover:bg-surface-container-high"
                            :class="open && 'text-primary'">
                        <span class="material-symbols-outlined">{{ $entry['icon'] }}</span>
                        <span class="font-label-md text-label-md">{{ $entry['label'] }}</span>
                        <span class="material-symbols-outlined ml-auto text-[20px] transition-transform duration-200"
                              :class="open && 'rotate-180'">expand_more</span>
                    </button>

                    <div x-show="open" x-collapse x-cloak
                         class="mt-xs space-y-px border-l border-outline-variant pl-3 ml-[19px]">
                        @foreach ($entry['children'] as $child)
                            @php $active = $itemActive($child); @endphp
                            <a href="{{ $itemUrl($child) }}"
                               class="flex items-center gap-sm rounded-lg px-md py-2 text-label-md transition-colors
                                      {{ $active ? 'bg-secondary-container font-bold text-primary' : 'text-on-surface-variant hover:bg-surface-container-high' }}">
                                <span class="material-symbols-outlined text-[18px] opacity-80">{{ $child['icon'] ?? 'chevron_right' }}</span>
                                <span>{{ $child['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </nav>

    {{-- Footer actions --}}
    <div class="mt-auto space-y-xs border-t border-outline-variant pt-lg">
        <a href="#" class="flex items-center gap-md rounded-lg px-md py-sm text-on-surface-variant transition-colors hover:bg-surface-container-high">
            <span class="material-symbols-outlined">help</span>
            <span class="font-label-md text-label-md">Help &amp; Docs</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex w-full items-center gap-md rounded-lg px-md py-sm text-on-surface-variant transition-colors hover:bg-surface-container-high">
                <span class="material-symbols-outlined">logout</span>
                <span class="font-label-md text-label-md">Sign Out</span>
            </button>
        </form>
        <button class="mt-md flex w-full items-center justify-center gap-xs rounded-lg bg-primary px-md py-sm font-label-md text-white transition-all hover:opacity-90 active:scale-95">
            <span class="material-symbols-outlined text-[18px]">support_agent</span>
            Support Ticket
        </button>
    </div>
</aside>
