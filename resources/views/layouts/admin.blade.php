<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Dashboard') | {{ config('app.name', 'Education ERP') }}</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#004ac6",
                        "primary-container": "#2563eb",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#eeefff",
                        "primary-fixed": "#dbe1ff",
                        "primary-fixed-dim": "#b4c5ff",
                        "secondary": "#505f76",
                        "secondary-container": "#d0e1fb",
                        "on-secondary": "#ffffff",
                        "on-secondary-container": "#54647a",
                        "tertiary": "#006242",
                        "tertiary-container": "#007d55",
                        "on-tertiary": "#ffffff",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6",
                        "on-error": "#ffffff",
                        "on-error-container": "#93000a",
                        "background": "#f7f9fb",
                        "on-background": "#191c1e",
                        "surface": "#f7f9fb",
                        "surface-dim": "#d8dadc",
                        "surface-bright": "#f7f9fb",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f2f4f6",
                        "surface-container": "#eceef0",
                        "surface-container-high": "#e6e8ea",
                        "surface-container-highest": "#e0e3e5",
                        "surface-variant": "#e0e3e5",
                        "on-surface": "#191c1e",
                        "on-surface-variant": "#434655",
                        "outline": "#737686",
                        "outline-variant": "#c3c6d7",
                        "inverse-surface": "#2d3133",
                        "inverse-on-surface": "#eff1f3",
                        "inverse-primary": "#b4c5ff",
                    },
                    borderRadius: { DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", full: "9999px" },
                    spacing: { xs: "4px", sm: "8px", base: "4px", md: "16px", lg: "24px", xl: "32px", gutter: "20px", "container-max": "1440px" },
                    fontFamily: {
                        "body-md": ["Inter"], "body-lg": ["Inter"], "label-sm": ["Inter"], "label-md": ["Inter"],
                        "headline-md": ["Inter"], "headline-lg": ["Inter"], "display-lg": ["Inter"],
                    },
                },
            },
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; vertical-align: middle; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('head')
</head>

<body class="bg-background text-on-surface font-body-md selection:bg-primary-container selection:text-white"
      x-data="{ sidebarOpen: false }">

    {{-- Mobile backdrop --}}
    <div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false"
         x-transition.opacity
         class="fixed inset-0 z-40 bg-on-surface/40 backdrop-blur-sm lg:hidden"></div>

    @include('partials.sidebar')

    {{-- Main view area --}}
    <main class="lg:ml-[260px] min-h-screen flex flex-col">
        @include('partials.header')

        <div class="flex-1 p-lg">
            @yield('content')
        </div>

        @include('partials.footer')
    </main>

    @stack('scripts')
</body>
</html>
