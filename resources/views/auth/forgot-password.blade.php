@extends('layouts.guest')

@section('title', 'Forgot Password')

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
            <div class="space-y-6 p-8">
                <div class="space-y-2">
                    <h1 class="text-headline-lg font-semibold text-on-surface">Forgot Password?</h1>
                    <p class="text-body-md text-on-surface-variant">
                        Enter the email associated with your account and we'll send a secure recovery link.
                    </p>
                </div>

                @if (session('status'))
                    <div class="flex gap-3 rounded-lg border border-tertiary/30 bg-tertiary/10 p-4">
                        <span class="material-symbols-outlined text-tertiary">mark_email_read</span>
                        <p class="text-body-md text-tertiary">{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf
                    <div class="space-y-1">
                        <label for="email" class="text-label-sm uppercase tracking-wider text-on-surface-variant">Email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">person</span>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                   placeholder="e.g. j.doe@educore.edu"
                                   class="w-full rounded-lg border border-outline-variant bg-surface py-3 pl-10 pr-4 text-body-md outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"/>
                        </div>
                        @error('email')<p class="text-label-sm text-error">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-6 py-3 text-label-md font-bold text-on-primary transition-all hover:bg-opacity-90 active:opacity-80">
                        Send Reset Link
                        <span class="material-symbols-outlined text-[18px]">send</span>
                    </button>

                    <a href="{{ route('login') }}" class="block text-center text-label-md text-primary hover:underline">Back to Login</a>
                </form>

                <div class="flex gap-3 rounded-lg border border-outline-variant/50 bg-surface-container-low p-4">
                    <span class="material-symbols-outlined text-primary">info</span>
                    <p class="text-body-md text-on-surface-variant">
                        Links expire after 60 minutes for security. Check your junk folder if it doesn't arrive.
                    </p>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
