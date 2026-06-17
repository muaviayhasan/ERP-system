<header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-outline-variant bg-surface-container-lowest px-lg shadow-sm">
    <div class="flex items-center gap-md">
        {{-- Mobile sidebar toggle --}}
        <button @click="sidebarOpen = true" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low lg:hidden">
            <span class="material-symbols-outlined">menu</span>
        </button>

        {{-- Search --}}
        <div class="relative hidden sm:block">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" placeholder="Quick search..."
                   class="w-64 rounded-lg border-none bg-surface-container-low py-2 pl-10 pr-4 text-body-md transition-all focus:ring-2 focus:ring-primary/20"/>
        </div>
    </div>

    <div class="flex items-center gap-md">
        <div class="hidden rounded-full bg-secondary-container px-md py-xs text-label-md font-bold text-primary md:block">
            Academic Year 2024-25
        </div>
        <button class="p-2 text-on-surface-variant transition-colors hover:text-primary">
            <span class="material-symbols-outlined">notifications</span>
        </button>
        <button class="p-2 text-on-surface-variant transition-colors hover:text-primary">
            <span class="material-symbols-outlined">apps</span>
        </button>
        <div class="mx-xs hidden h-8 w-px bg-outline-variant sm:block"></div>

        {{-- Profile --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center gap-sm">
                <div class="hidden text-right sm:block">
                    <p class="text-label-md font-bold leading-none">{{ auth()->user()?->name ?? 'Admin User' }}</p>
                    <p class="text-label-sm text-on-surface-variant">{{ auth()->user()?->getRoleNames()->first() ?? 'System Administrator' }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-full border border-outline-variant bg-primary-container text-white">
                    <span class="material-symbols-outlined">person</span>
                </div>
            </button>

            <div x-show="open" x-cloak @click.outside="open = false" x-transition
                 class="absolute right-0 mt-2 w-48 rounded-xl border border-outline-variant bg-surface-container-lowest py-1 shadow-lg">
                <a href="#" class="flex items-center gap-sm px-md py-2 text-label-md text-on-surface-variant hover:bg-surface-container-low">
                    <span class="material-symbols-outlined text-[18px]">account_circle</span> Profile
                </a>
                <a href="#" class="flex items-center gap-sm px-md py-2 text-label-md text-on-surface-variant hover:bg-surface-container-low">
                    <span class="material-symbols-outlined text-[18px]">settings</span> Settings
                </a>
                <div class="my-1 border-t border-outline-variant"></div>
                <button type="button" @click="open = false; $dispatch('open-logout-modal')"
                        class="flex w-full cursor-pointer items-center gap-sm px-md py-2 text-left text-label-md text-error hover:bg-error-container/30">
                    <span class="material-symbols-outlined text-[18px]">logout</span> Sign Out
                </button>
            </div>
        </div>
    </div>
</header>
