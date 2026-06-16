@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    {{-- Page header --}}
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Dashboard</h2>
            <p class="text-body-md text-on-surface-variant">Overview of academic, financial, and operational activity.</p>
        </div>
        <div class="flex items-center gap-sm">
            <button class="flex items-center gap-xs rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2.5 text-label-md font-bold text-on-surface-variant transition-colors hover:bg-surface-container-low">
                <span class="material-symbols-outlined text-[20px]">download</span> Export
            </button>
            <button class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-white transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">add</span> Quick Action
            </button>
        </div>
    </div>

    {{-- KPI row (layout placeholders) --}}
    <div class="mb-lg grid grid-cols-1 gap-md sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['label' => 'Total Students', 'value' => '—', 'icon' => 'groups', 'tone' => 'primary'],
            ['label' => 'Total Staff', 'value' => '—', 'icon' => 'badge', 'tone' => 'tertiary'],
            ['label' => 'Fees Collected', 'value' => '—', 'icon' => 'payments', 'tone' => 'primary'],
            ['label' => 'Pending Dues', 'value' => '—', 'icon' => 'warning', 'tone' => 'error'],
        ] as $kpi)
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-md shadow-sm">
                <div class="mb-2 flex items-start justify-between">
                    <span class="text-label-sm uppercase tracking-wider text-on-surface-variant">{{ $kpi['label'] }}</span>
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-{{ $kpi['tone'] }}/10 text-{{ $kpi['tone'] }}">
                        <span class="material-symbols-outlined text-[20px]">{{ $kpi['icon'] }}</span>
                    </div>
                </div>
                <span class="font-display-lg text-display-lg text-on-surface">{{ $kpi['value'] }}</span>
                <p class="mt-1 text-[11px] text-on-surface-variant">Awaiting data binding</p>
            </div>
        @endforeach
    </div>

    {{-- Content panels (layout placeholders) --}}
    <div class="grid grid-cols-1 gap-md lg:grid-cols-3">
        <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm lg:col-span-2">
            <div class="mb-md flex items-center justify-between">
                <h3 class="font-headline-md text-headline-md text-on-surface">Recent Activity</h3>
                <button class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low">
                    <span class="material-symbols-outlined">more_horiz</span>
                </button>
            </div>
            <div class="flex h-64 items-center justify-center rounded-lg border border-dashed border-outline-variant text-on-surface-variant">
                <div class="text-center">
                    <span class="material-symbols-outlined text-[40px] opacity-40">insights</span>
                    <p class="mt-2 text-body-md">Content region — ready for data</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
            <h3 class="mb-md font-headline-md text-headline-md text-on-surface">Quick Links</h3>
            <div class="space-y-xs">
                @foreach (['Enroll Student' => 'person_add', 'Collect Fee' => 'payments', 'Mark Attendance' => 'how_to_reg', 'Create Notice' => 'campaign'] as $label => $icon)
                    <a href="#" class="flex items-center gap-md rounded-lg border border-outline-variant px-md py-3 text-label-md text-on-surface-variant transition-colors hover:bg-surface-container-low hover:text-primary">
                        <span class="material-symbols-outlined">{{ $icon }}</span> {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Form components preview: Select2 + input masks (verifies the install) --}}
    <div class="mt-lg rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
        <h3 class="mb-1 font-headline-md text-headline-md text-on-surface">Form Components</h3>
        <p class="mb-md text-body-md text-on-surface-variant">Select2 on all selects, with CNIC &amp; phone input masks — applied automatically.</p>
        <div class="grid grid-cols-1 gap-md md:grid-cols-3">
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Campus (Select2)</label>
                <select data-allow-clear placeholder="Select a campus...">
                    <option></option>
                    <option>Main Campus</option>
                    <option>North Wing</option>
                    <option>East Side Extension</option>
                    <option>City Campus</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">CNIC</label>
                <input type="text" data-mask="cnic" maxlength="15" placeholder="32301-0000000-0"
                       class="w-full rounded-lg border border-outline-variant px-md py-2 outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"/>
            </div>
            <div class="space-y-1">
                <label class="text-label-sm font-bold text-on-surface-variant">Phone</label>
                <input type="text" data-mask="phone" maxlength="12" placeholder="0300-0000000"
                       class="w-full rounded-lg border border-outline-variant px-md py-2 outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20"/>
            </div>
        </div>
    </div>
@endsection
