@extends('layouts.admin')

@section('title', 'Backup & Restore')

@section('content')
    <div class="mb-lg flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Backup &amp; Restore</h2>
            <p class="text-body-md text-on-surface-variant">
                Create and download database backups. Current driver:
                <span class="font-bold text-on-surface">{{ $driver }}</span>
            </p>
        </div>
        <form method="POST" action="{{ route('settings.backup.create') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-2 rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined text-[18px]">backup</span>
                Create Backup Now
            </button>
        </form>
    </div>

    <x-settings.section title="Available Backups" icon="database"
        desc="Backups are stored on the server under storage/app/backups.">
        @if (count($backups) === 0)
            <div class="flex flex-col items-center justify-center gap-2 py-12 text-center">
                <span class="material-symbols-outlined text-[40px] text-on-surface-variant opacity-40">inventory_2</span>
                <p class="text-body-md text-on-surface-variant">No backups yet. Create your first backup above.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="border-b border-outline-variant text-label-sm uppercase tracking-wide text-on-surface-variant">
                        <tr>
                            <th class="px-4 py-3">File</th>
                            <th class="px-4 py-3">Size</th>
                            <th class="px-4 py-3">Created</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant text-body-md">
                        @foreach ($backups as $backup)
                            <tr class="hover:bg-surface-container-low/50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[20px] text-primary">description</span>
                                        <span class="font-mono text-label-md">{{ $backup['name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-on-surface-variant">{{ $backup['size'] }}</td>
                                <td class="px-4 py-3 text-on-surface-variant">{{ $backup['date'] }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('settings.backup.download', $backup['name']) }}"
                                           class="flex items-center gap-1 rounded-lg border border-outline-variant px-3 py-1.5 text-label-md font-bold text-on-surface-variant transition-colors hover:bg-surface-container-high">
                                            <span class="material-symbols-outlined text-[18px]">download</span> Download
                                        </a>
                                        <form method="POST" action="{{ route('settings.backup.destroy', $backup['name']) }}"
                                              onsubmit="return confirm('Delete this backup permanently?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="flex items-center gap-1 rounded-lg border border-error/30 px-3 py-1.5 text-label-md font-bold text-error transition-colors hover:bg-error/10">
                                                <span class="material-symbols-outlined text-[18px]">delete</span> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-settings.section>
@endsection
