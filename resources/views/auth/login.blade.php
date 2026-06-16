@extends('layouts.guest')

@section('title', 'Sign In')

@section('content')
<div class="flex min-h-screen">
    {{-- Left: branding panel --}}
    <section class="relative hidden w-1/2 flex-col justify-between overflow-hidden p-16 lg:flex"
             style="background: linear-gradient(135deg, #3525cd 0%, #4d44e3 50%, #0b1c30 100%);">
        <div class="pointer-events-none absolute inset-0 opacity-10">
            <div class="absolute right-[-10%] top-[-10%] h-[600px] w-[600px] rounded-full border-[60px] border-white"></div>
            <div class="absolute bottom-[-20%] left-[-10%] h-[800px] w-[800px] rounded-full border-[40px] border-white"></div>
        </div>
        <div class="relative z-10">
            <div class="mb-8 flex items-center gap-3">
                <span class="material-symbols-outlined text-5xl text-white" style="font-variation-settings: 'FILL' 1;">school</span>
                <h1 class="text-3xl font-bold tracking-tight text-white">{{ config('app.name', 'Education ERP') }}</h1>
            </div>
            <p class="max-w-md text-2xl font-semibold text-primary-fixed-dim">
                Empowering Academic Excellence Through Integrated Intelligence
            </p>
        </div>
        <div class="relative z-10 grid grid-cols-2 gap-4">
            @foreach (['person_search' => 'Student Lifecycle', 'account_balance' => 'Financial Governance', 'event_available' => 'Attendance Intelligence', 'analytics' => 'Performance Analytics'] as $icon => $label)
                <div class="flex items-center gap-3 rounded-lg border border-white/10 bg-white/5 p-4 backdrop-blur">
                    <span class="material-symbols-outlined text-primary-fixed-dim">{{ $icon }}</span>
                    <span class="text-label-md text-white">{{ $label }}</span>
                </div>
            @endforeach
        </div>
        <p class="relative z-10 text-label-sm text-white/50">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </section>

    {{-- Right: auth form --}}
    <main class="relative flex w-full flex-col items-center justify-center bg-surface p-6 lg:w-1/2">
        <div class="absolute right-0 top-0 p-8 opacity-20 pointer-events-none">
            <span class="material-symbols-outlined text-[120px] text-primary-container">shield</span>
        </div>

        <div class="flex w-full max-w-[440px] flex-col gap-8">
            <div class="text-center md:text-left">
                <h2 class="mb-2 text-3xl font-bold text-on-surface">Welcome Back</h2>
                <p class="text-body-md text-on-surface-variant">Sign in to access your institutional portal</p>
            </div>

            @if (session('status'))
                <div class="rounded-lg border border-tertiary/30 bg-tertiary/10 px-4 py-3 text-body-md text-tertiary">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5" x-data="{ show: false }">
                @csrf

                {{-- Email / username --}}
                <div class="flex flex-col gap-2">
                    <label class="text-label-sm text-on-surface-variant" for="email">Email or Username</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">mail</span>
                        <input id="email" name="email" type="text" value="{{ old('email') }}" autofocus required
                               placeholder="name@institution.edu"
                               class="h-12 w-full rounded-lg border border-outline-variant bg-white px-10 text-body-md outline-none transition-all placeholder:text-outline focus:border-primary focus:ring-2 focus:ring-primary/20"/>
                    </div>
                    @error('email')<p class="text-label-sm text-error">{{ $message }}</p>@enderror
                </div>

                {{-- Password --}}
                <div class="flex flex-col gap-2">
                    <label class="text-label-sm text-on-surface-variant" for="password">Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">lock</span>
                        <input id="password" name="password" :type="show ? 'text' : 'password'" required
                               placeholder="••••••••"
                               class="h-12 w-full rounded-lg border border-outline-variant bg-white px-10 text-body-md outline-none transition-all placeholder:text-outline focus:border-primary focus:ring-2 focus:ring-primary/20"/>
                        <button type="button" @click="show = !show"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant transition-colors hover:text-primary">
                            <span class="material-symbols-outlined" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                        </button>
                    </div>
                    @error('password')<p class="text-label-sm text-error">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex cursor-pointer items-center gap-2">
                        <input type="checkbox" name="remember" class="h-5 w-5 rounded border-outline-variant text-primary focus:ring-primary"/>
                        <span class="text-body-md text-on-surface-variant">Remember Me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-label-md text-primary hover:underline">Forgot Password?</a>
                </div>

                <button type="submit"
                        class="flex h-12 w-full items-center justify-center gap-2 rounded-lg bg-primary text-label-md font-bold text-white shadow-md transition-all hover:bg-opacity-90 active:scale-[0.98]">
                    Sign In
                    <span class="material-symbols-outlined text-lg">login</span>
                </button>
            </form>

            <footer class="mt-4 flex flex-col items-center gap-4">
                <nav class="flex gap-4 text-label-sm text-on-surface-variant">
                    <a href="#" class="hover:text-primary">Privacy Policy</a>
                    <span class="text-outline-variant">|</span>
                    <a href="#" class="hover:text-primary">Terms of Service</a>
                    <span class="text-outline-variant">|</span>
                    <a href="#" class="hover:text-primary">Support</a>
                </nav>
                <p class="text-label-sm text-on-surface-variant opacity-50">v1.0.0</p>
            </footer>
        </div>
    </main>
</div>
@endsection
