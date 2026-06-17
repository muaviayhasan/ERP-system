@extends('layouts.admin')

@section('title', 'Guardian Management')

@php
    use App\Http\Controllers\Admin\GuardianController;
    $statusStyles = [
        'active' => 'bg-tertiary/10 text-tertiary',
        'inactive' => 'bg-outline-variant/40 text-on-surface-variant',
    ];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Guardian Management</h2>
            <p class="text-body-md text-on-surface-variant">Manage students' legal guardians, emergency contacts, and primary fee payers.</p>
        </div>
        @can('guardians.create')
            <a href="{{ route('guardians.create') }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">person_add</span> Add Guardian
            </a>
        @endcan
    </div>

    {{-- Stats --}}
    <div class="mb-lg grid grid-cols-1 gap-md md:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['Total Guardians', $stats['total'], 'group', 'bg-primary/10 text-primary'],
            ['Primary Payers', $stats['payers'], 'account_balance_wallet', 'bg-tertiary/10 text-tertiary'],
            ['Emergency Authorized', $stats['emergency'], 'emergency', 'bg-error/10 text-error'],
            ['Phone Verified', $stats['verified'], 'verified', 'bg-secondary-container text-on-secondary-container'],
        ] as [$label, $value, $icon, $tone])
            <div class="flex items-center justify-between rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <div>
                    <p class="mb-1 text-label-sm uppercase tracking-wider text-on-surface-variant">{{ $label }}</p>
                    <h3 class="font-headline-md text-headline-md text-on-surface">{{ number_format($value) }}</h3>
                </div>
                <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $tone }}">
                    <span class="material-symbols-outlined">{{ $icon }}</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-4">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone, or CNIC..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="relationship" data-allow-clear placeholder="All Relationships"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Relationships</option>
            @foreach (GuardianController::RELATIONSHIPS as $rel)
                <option value="{{ $rel }}" @selected(request('relationship') === $rel)>{{ ucfirst($rel) }}</option>
            @endforeach
        </select>
        <select name="status" data-allow-clear placeholder="All Statuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Statuses</option>
            @foreach (GuardianController::STATUSES as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Guardian</th>
                        <th class="px-lg py-4 font-bold">Relationship</th>
                        <th class="px-lg py-4 font-bold">CNIC / ID</th>
                        <th class="px-lg py-4 font-bold">Contact</th>
                        <th class="px-lg py-4 font-bold">Linked</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($guardians as $guardian)
                        @php
                            $initials = Str::of($guardian->full_name)->explode(' ')->take(2)->map(fn ($w) => Str::substr($w, 0, 1))->implode('');
                        @endphp
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary-container font-bold text-on-primary">{{ Str::upper($initials) ?: 'G' }}</div>
                                    <div>
                                        <p class="font-bold text-on-surface">{{ $guardian->full_name }}</p>
                                        <p class="text-label-sm text-on-surface-variant">{{ $guardian->email ?? '—' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $guardian->relationship ? ucfirst($guardian->relationship) : '—' }}</td>
                            <td class="px-lg py-3 text-on-surface-variant">{{ $guardian->cnic ?? '—' }}</td>
                            <td class="px-lg py-3">
                                <p class="text-on-surface">{{ $guardian->phone }}</p>
                                <div class="flex flex-wrap gap-1 pt-1">
                                    @if ($guardian->phone_verified)
                                        <span class="inline-flex items-center gap-0.5 text-label-sm text-tertiary"><span class="material-symbols-outlined text-[14px]">verified</span> Verified</span>
                                    @endif
                                    @if ($guardian->is_emergency_authorized)
                                        <span class="inline-flex items-center gap-0.5 text-label-sm text-error"><span class="material-symbols-outlined text-[14px]">emergency</span> Emergency</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-lg py-3">
                                @if ($guardian->students_count)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-surface-container-high px-2.5 py-0.5 text-label-sm font-bold text-on-surface-variant">
                                        <span class="material-symbols-outlined text-[14px]">group</span> {{ $guardian->students_count }}
                                    </span>
                                @else
                                    <span class="text-label-sm text-outline">None</span>
                                @endif
                            </td>
                            <td class="px-lg py-3">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $statusStyles[$guardian->status] ?? $statusStyles['inactive'] }}">
                                    {{ ucfirst($guardian->status ?? 'inactive') }}
                                </span>
                            </td>
                            <td class="px-lg py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('guardians.edit')
                                        <a href="{{ route('guardians.edit', $guardian) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Edit">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endcan
                                    @can('guardians.delete')
                                        <form method="POST" action="{{ route('guardians.destroy', $guardian) }}" onsubmit="return confirm('Delete {{ $guardian->full_name }}?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rounded-lg p-2 text-on-surface-variant hover:bg-error/10 hover:text-error" title="Delete">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-lg py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">family_restroom</span>
                                No guardians found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
            {{ $guardians->links() }}
        </div>
    </div>
@endsection
