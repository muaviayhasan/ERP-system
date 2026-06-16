@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
<div class="flex min-h-screen flex-col">
    <header class="flex w-full items-center justify-between border-b border-outline-variant px-8 py-6">
        <a href="{{ route('login') }}" class="flex items-center gap-2">
            <span class="material-symbols-outlined text-3xl text-primary" style="font-variation-settings: 'FILL' 1;">school</span>
            <span class="text-headline-md font-bold text-primary">{{ config('app.name', 'Education ERP') }}</span>
        </a>
    </header>

    <main class="flex flex-1 items-center justify-center p-6">
        <div class="w-full max-w-md overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
            <div class="space-y-6 p-8" x-data="{ show: false }">
                <div class="space-y-2">
                    <h1 class="text-headline-lg font-semibold text-on-surface">Secure your Account</h1>
                    <p class="text-body-md text-on-surface-variant">Create a strong new password you haven't used before.</p>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}"/>

                    <div class="space-y-1">
                        <label for="email" class="text-label-sm uppercase tracking-wider text-on-surface-variant">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required readonly
                               class="w-full cursor-not-allowed rounded-lg border border-outline-variant bg-surface-container-low px-4 py-3 text-body-md text-on-surface-variant outline-none"/>
                        @error('email')<p class="text-label-sm text-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="text-label-sm uppercase tracking-wider text-on-surface-variant">New Password</label>
                        <div class="relative">
                            <input id="password" name="password" :type="show ? 'text' : 'password'" required minlength="8"
                                   class="w-full rounded-lg border border-outline-variant bg-surface px-4 py-3 text-body-md outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"/>
                            <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary">
                                <span class="material-symbols-outlined" x-text="show ? 'visibility_off' : 'visibility'">visibility</span>
                            </button>
                        </div>
                        @error('password')<p class="text-label-sm text-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-1">
                        <label for="password_confirmation" class="text-label-sm uppercase tracking-wider text-on-surface-variant">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" :type="show ? 'text' : 'password'" required minlength="8"
                               class="w-full rounded-lg border border-outline-variant bg-surface px-4 py-3 text-body-md outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"/>
                    </div>

                    <button type="submit"
                            class="w-full rounded-lg bg-primary px-6 py-3 text-label-md font-bold text-on-primary transition-all hover:bg-opacity-90 active:opacity-80">
                        Reset Password
                    </button>
                    <a href="{{ route('login') }}" class="block text-center text-label-md text-primary hover:underline">Back to Login</a>
                </form>
            </div>
        </div>
    </main>
</div>
@endsection
