<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Under Maintenance | {{ config('app.name', 'Education ERP') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-screen items-center justify-center bg-background p-6 font-body-md text-on-surface">
    <div class="max-w-md rounded-2xl border border-outline-variant bg-surface-container-lowest p-8 text-center shadow-sm">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10 text-primary">
            <span class="material-symbols-outlined text-[36px]">construction</span>
        </div>
        <h1 class="font-headline-lg text-headline-lg text-on-surface">We&rsquo;ll be right back</h1>
        <p class="mt-2 text-body-md text-on-surface-variant">
            {{ config('app.name', 'The system') }} is currently undergoing scheduled maintenance.
            Please check back shortly.
        </p>
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg border border-outline-variant px-lg py-2.5 font-bold text-on-surface-variant transition-colors hover:bg-surface-container-high">
                <span class="material-symbols-outlined text-[18px]">logout</span> Sign out
            </button>
        </form>
    </div>
</body>
</html>
