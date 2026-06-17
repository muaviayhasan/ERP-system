<!DOCTYPE html>
<html class="{{ setting('user_defaults', 'default_theme', 'light') === 'dark' ? 'dark' : 'light' }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Dashboard') | {{ config('app.name', 'Education ERP') }}</title>

    {{-- Fonts + icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    @php $favicon = setting('general', 'favicon'); @endphp
    @if ($favicon)
        <link rel="icon" href="{{ Storage::url($favicon) }}"/>
    @endif

    {{-- Compiled assets (Tailwind + Select2 + masks) via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

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
            @include('partials.flash')
            @yield('content')
        </div>

        @include('partials.footer')
    </main>

    @include('partials.logout-modal')

    @stack('scripts')
    @livewireScripts
</body>
</html>
