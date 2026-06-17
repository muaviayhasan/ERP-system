@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    {{-- Page header --}}
    <div class="mb-xl flex flex-col items-start justify-between gap-md sm:flex-row sm:items-center">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Institutional Overview</h2>
            <p class="flex items-center gap-2 text-body-md text-on-surface-variant">
                <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                {{ now()->format('l, d F Y') }}
            </p>
        </div>
        <button class="flex items-center gap-2 rounded-lg border border-outline-variant bg-surface-container-lowest px-lg py-2.5 text-label-md font-bold text-on-surface-variant shadow-sm transition-colors hover:bg-surface-container-low">
            <span class="material-symbols-outlined">download</span>
            Download Report
        </button>
    </div>

    {{-- KPI row --}}
    <div class="mb-xl grid grid-cols-1 gap-md md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
        {{-- Total Students --}}
        <div class="rounded-lg border border-outline-variant bg-surface-container-lowest p-md shadow-sm">
            <div class="mb-sm flex items-start justify-between">
                <div class="rounded-lg bg-primary/10 p-2">
                    <span class="material-symbols-outlined text-primary">group</span>
                </div>
                <span class="rounded-full bg-tertiary/10 px-2 py-0.5 text-[10px] font-bold text-tertiary">{{ $metrics['students']['change'] }}</span>
            </div>
            <p class="text-label-sm uppercase tracking-wider text-on-surface-variant">Total Students</p>
            <h3 class="mt-1 font-headline-md text-headline-md text-on-surface">{{ number_format($metrics['students']['total']) }}</h3>
            <div class="mt-md flex gap-4">
                <div>
                    <p class="text-[10px] text-on-surface-variant">School</p>
                    <p class="text-label-md font-bold">{{ number_format($metrics['students']['school']) }}</p>
                </div>
                <div class="h-8 w-px bg-outline-variant"></div>
                <div>
                    <p class="text-[10px] text-on-surface-variant">College</p>
                    <p class="text-label-md font-bold">{{ number_format($metrics['students']['college']) }}</p>
                </div>
            </div>
        </div>

        {{-- Total Staff --}}
        <div class="rounded-lg border border-outline-variant bg-surface-container-lowest p-md shadow-sm">
            <div class="mb-sm flex items-start justify-between">
                <div class="rounded-lg bg-secondary-container p-2">
                    <span class="material-symbols-outlined text-secondary">badge</span>
                </div>
            </div>
            <p class="text-label-sm uppercase tracking-wider text-on-surface-variant">Total Staff</p>
            <h3 class="mt-1 font-headline-md text-headline-md text-on-surface">{{ number_format($metrics['staff']['total']) }}</h3>
            <div class="mt-md flex gap-4">
                <div>
                    <p class="text-[10px] text-on-surface-variant">Teaching</p>
                    <p class="text-label-md font-bold">{{ number_format($metrics['staff']['teaching']) }}</p>
                </div>
                <div class="h-8 w-px bg-outline-variant"></div>
                <div>
                    <p class="text-[10px] text-on-surface-variant">Admin</p>
                    <p class="text-label-md font-bold">{{ number_format($metrics['staff']['admin']) }}</p>
                </div>
            </div>
        </div>

        {{-- Today's Attendance --}}
        <div class="rounded-lg border border-outline-variant bg-surface-container-lowest p-md shadow-sm">
            <div class="mb-sm flex items-start justify-between">
                <div class="rounded-lg bg-tertiary/10 p-2">
                    <span class="material-symbols-outlined text-tertiary">check_circle</span>
                </div>
                <span class="text-label-md font-bold text-tertiary">{{ $metrics['attendance']['rate'] }}%</span>
            </div>
            <p class="text-label-sm uppercase tracking-wider text-on-surface-variant">Today's Attendance</p>
            <div class="mt-1 flex items-end gap-2">
                <h3 class="font-headline-md text-headline-md text-on-surface">{{ number_format($metrics['attendance']['present']) }}</h3>
                <span class="mb-1 text-body-md text-on-surface-variant">/ {{ number_format($metrics['attendance']['total']) }}</span>
            </div>
            <div class="mt-md flex justify-between text-[10px] text-on-surface-variant">
                <span class="flex items-center gap-1"><span class="h-1.5 w-1.5 rounded-full bg-error"></span> {{ $metrics['attendance']['absent'] }} Absent</span>
                <span class="flex items-center gap-1"><span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span> {{ $metrics['attendance']['late'] }} Late</span>
            </div>
        </div>

        {{-- Fee Collection --}}
        <div class="rounded-lg border border-outline-variant bg-surface-container-lowest p-md shadow-sm">
            <div class="mb-sm flex items-start justify-between">
                <div class="rounded-lg bg-tertiary/10 p-2">
                    <span class="material-symbols-outlined text-tertiary">payments</span>
                </div>
            </div>
            <p class="text-label-sm uppercase tracking-wider text-on-surface-variant">Fee Collection</p>
            <h3 class="mt-1 font-headline-md text-headline-md text-on-surface">{{ format_money($metrics['fees']['received']) }} <span class="text-label-md font-normal">Received</span></h3>
            @php $feePct = (int) round($metrics['fees']['received'] / max($metrics['fees']['target'], 1) * 100); @endphp
            <div class="mt-md">
                <div class="mb-1 flex justify-between text-[10px]">
                    <span class="text-on-surface-variant">Target: {{ format_money($metrics['fees']['target']) }}</span>
                    <span class="font-bold text-orange-600">{{ format_money($metrics['fees']['pending']) }} Pending</span>
                </div>
                <div class="h-2 w-full overflow-hidden rounded-full bg-surface-container-high">
                    <div class="h-full bg-primary" style="width: {{ $feePct }}%"></div>
                </div>
            </div>
        </div>

        {{-- Monthly Expenses --}}
        <div class="rounded-lg border border-outline-variant bg-surface-container-lowest p-md shadow-sm">
            <div class="mb-sm flex items-start justify-between">
                <div class="rounded-lg bg-error-container p-2">
                    <span class="material-symbols-outlined text-error">trending_up</span>
                </div>
            </div>
            <p class="text-label-sm uppercase tracking-wider text-on-surface-variant">Monthly Expenses</p>
            <h3 class="mt-1 font-headline-md text-headline-md text-on-surface">{{ format_money($metrics['expenses']['current']) }}</h3>
            <p class="mt-md text-[10px] text-on-surface-variant">
                <span class="font-bold text-error">{{ $metrics['expenses']['change'] }}</span> from {{ format_money($metrics['expenses']['previous']) }} last month
            </p>
            <div class="mt-2 h-1 overflow-hidden rounded-full bg-surface-container-high">
                <div class="h-full w-[90%] bg-error"></div>
            </div>
        </div>
    </div>

    {{-- Body grid --}}
    <div class="grid grid-cols-1 gap-xl xl:grid-cols-12">
        {{-- Charts + lists column --}}
        <div class="flex flex-col gap-xl xl:col-span-9">
            {{-- Analytics grid --}}
            <div class="grid grid-cols-1 gap-lg lg:grid-cols-2">
                {{-- Fee collection trend --}}
                <div class="flex h-[320px] flex-col rounded-lg border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                    <div class="mb-lg flex items-center justify-between">
                        <h3 class="text-label-md font-bold text-on-surface">Fee Collection Trend</h3>
                        <select class="rounded border-none bg-surface-container-low px-2 py-1 text-label-sm">
                            <option>Annual View</option>
                        </select>
                    </div>
                    <div class="relative flex flex-1 items-end gap-1">
                        <div class="absolute inset-0 flex items-center justify-center opacity-10">
                            <span class="material-symbols-outlined text-[120px]">show_chart</span>
                        </div>
                        @foreach (['75%', '66%', '50%', '60%', '80%', '83%'] as $h)
                            <div class="flex-1 rounded-t-sm border-t-2 border-primary bg-primary/10" style="height: {{ $h }}"></div>
                        @endforeach
                    </div>
                </div>

                {{-- Weekly attendance --}}
                <div class="flex h-[320px] flex-col rounded-lg border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                    <div class="mb-lg flex items-center justify-between">
                        <h3 class="text-label-md font-bold text-on-surface">Weekly Attendance</h3>
                        <div class="flex gap-4">
                            <span class="flex items-center gap-1 text-[10px] text-on-surface-variant"><span class="h-2 w-2 rounded-full bg-primary"></span> Present</span>
                            <span class="flex items-center gap-1 text-[10px] text-on-surface-variant"><span class="h-2 w-2 rounded-full bg-error"></span> Absent</span>
                        </div>
                    </div>
                    <div class="flex flex-1 items-end justify-between gap-4 px-md">
                        @foreach ($metrics['weekly_attendance'] as $day)
                            <div class="flex w-8 flex-col items-center gap-1">
                                <div class="w-full rounded-t bg-primary" style="height: {{ $day['present'] }}%"></div>
                                <div class="w-full rounded-t bg-error" style="height: {{ $day['absent'] }}%"></div>
                                <span class="mt-2 text-[10px]">{{ $day['day'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Expense vs income --}}
                <div class="flex h-[320px] flex-col rounded-lg border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                    <h3 class="mb-lg text-label-md font-bold text-on-surface">Expense vs Income (Last 6m)</h3>
                    <div class="flex flex-1 items-center justify-center">
                        <div class="relative flex h-full w-full items-end gap-8 px-4">
                            @foreach ([['75%', '66%'], ['80%', '75%'], ['100%', '50%'], ['83%', '90%']] as $pair)
                                <div class="flex flex-1 items-end gap-1" style="height: {{ $pair[0] }}">
                                    <div class="h-full w-4 rounded-t bg-tertiary-container"></div>
                                    <div class="w-4 rounded-t bg-error-container" style="height: {{ $pair[1] }}"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Scholarship distribution --}}
                <div class="flex h-[320px] flex-col rounded-lg border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                    <h3 class="mb-lg text-label-md font-bold text-on-surface">Scholarship Distribution</h3>
                    <div class="flex flex-1 items-center gap-8">
                        <div class="relative flex h-32 w-32 items-center justify-center rounded-full border-[16px] border-primary">
                            <div class="absolute inset-0 rotate-45 rounded-full border-[16px] border-secondary border-t-transparent"></div>
                            <div class="absolute inset-0 -rotate-90 rounded-full border-[16px] border-tertiary border-l-transparent"></div>
                        </div>
                        <div class="flex flex-1 flex-col gap-2">
                            @foreach ($metrics['scholarships'] as $s)
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-2 text-label-sm"><span class="h-2.5 w-2.5 rounded-sm {{ $s['dot'] }}"></span> {{ $s['label'] }}</span>
                                    <span class="font-bold">{{ $s['pct'] }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Operational tables row --}}
            <div class="grid grid-cols-1 gap-lg lg:grid-cols-3">
                {{-- Recent fee payments --}}
                <div class="flex h-[400px] flex-col overflow-hidden rounded-lg border border-outline-variant bg-surface-container-lowest shadow-sm">
                    <div class="flex items-center justify-between border-b border-outline-variant bg-surface-container-low p-md">
                        <h3 class="text-label-md font-bold text-on-surface">Recent Fee Payments</h3>
                        <button class="text-[10px] text-primary hover:underline">View All</button>
                    </div>
                    <div class="custom-scrollbar flex-1 overflow-y-auto">
                        <table class="w-full text-left text-body-md">
                            <thead class="sticky top-0 border-b border-outline-variant bg-surface-container-lowest">
                                <tr class="text-[10px] uppercase tracking-tighter text-on-surface-variant">
                                    <th class="px-md py-3">Student</th>
                                    <th class="px-md py-3">Amount</th>
                                    <th class="px-md py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-surface-container">
                                @foreach ($metrics['recent_payments'] as $p)
                                    <tr>
                                        <td class="px-md py-3"><p class="font-bold">{{ $p['name'] }}</p><p class="text-[10px] text-on-surface-variant">{{ $p['detail'] }}</p></td>
                                        <td class="px-md py-3 font-medium">{{ format_money($p['amount']) }}</td>
                                        <td class="px-md py-3">
                                            @if ($p['status'] === 'Paid')
                                                <span class="rounded-full bg-tertiary/10 px-2 py-0.5 text-[10px] font-bold text-tertiary">Paid</span>
                                            @else
                                                <span class="rounded-full bg-orange-100 px-2 py-0.5 text-[10px] font-bold text-orange-600">{{ $p['status'] }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Recent admissions --}}
                <div class="flex h-[400px] flex-col overflow-hidden rounded-lg border border-outline-variant bg-surface-container-lowest shadow-sm">
                    <div class="flex items-center justify-between border-b border-outline-variant bg-surface-container-low p-md">
                        <h3 class="text-label-md font-bold text-on-surface">Recent Admissions</h3>
                        <button class="text-[10px] text-primary hover:underline">View All</button>
                    </div>
                    <div class="custom-scrollbar flex-1 overflow-y-auto">
                        <div class="divide-y divide-surface-container">
                            @foreach ($metrics['recent_admissions'] as $a)
                                <div class="flex items-center gap-3 p-md">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold {{ $a['tone'] }}">{{ $a['initials'] }}</div>
                                    <div class="flex-1">
                                        <p class="text-label-md">{{ $a['name'] }}</p>
                                        <p class="text-[10px] text-on-surface-variant">{{ $a['detail'] }} • {{ format_date($a['date']) }}</p>
                                    </div>
                                    <span class="material-symbols-outlined text-[18px] text-outline">chevron_right</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- System alerts --}}
                <div class="flex h-[400px] flex-col overflow-hidden rounded-lg border border-outline-variant bg-surface-container-lowest shadow-sm">
                    <div class="border-b border-outline-variant bg-surface-container-low p-md">
                        <h3 class="text-label-md font-bold text-on-surface">System Alerts</h3>
                    </div>
                    <div class="flex flex-1 flex-col gap-3 p-md">
                        @foreach ($metrics['alerts'] as $alert)
                            <div class="flex gap-3 rounded border-l-4 p-3 {{ $alert['classes'] }}">
                                <span class="material-symbols-outlined {{ $alert['icon_color'] }}">{{ $alert['icon'] }}</span>
                                <div>
                                    <p class="text-[11px] font-bold {{ $alert['title_color'] }}">{{ $alert['title'] }}</p>
                                    <p class="text-[10px] text-on-surface-variant">{{ $alert['body'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Right column --}}
        <div class="flex flex-col gap-lg xl:col-span-3">
            {{-- Quick actions --}}
            <div class="rounded-lg border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <h3 class="mb-lg text-label-md font-bold text-on-surface">Quick Actions</h3>
                <div class="grid grid-cols-1 gap-3">
                    @foreach ([
                        ['icon' => 'person_add', 'label' => 'Add Student', 'desc' => 'Enroll a new learner', 'tone' => 'bg-primary/10 text-primary'],
                        ['icon' => 'group_add', 'label' => 'Add Teacher', 'desc' => 'Onboard staff member', 'tone' => 'bg-tertiary/10 text-tertiary'],
                        ['icon' => 'payments', 'label' => 'Create Fee Plan', 'desc' => 'Generate new cycle', 'tone' => 'bg-secondary-container/40 text-secondary'],
                        ['icon' => 'how_to_reg', 'label' => 'Mark Attendance', 'desc' => 'Manual override', 'tone' => 'bg-error/10 text-error'],
                    ] as $action)
                        <button class="group flex items-center gap-md rounded-lg border border-outline-variant p-3 transition-all hover:bg-surface-container-low">
                            <div class="flex h-10 w-10 items-center justify-center rounded transition-transform group-hover:scale-110 {{ $action['tone'] }}">
                                <span class="material-symbols-outlined">{{ $action['icon'] }}</span>
                            </div>
                            <div class="text-left">
                                <p class="text-label-md">{{ $action['label'] }}</p>
                                <p class="text-[10px] text-on-surface-variant">{{ $action['desc'] }}</p>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Campus highlight card --}}
            <div class="group relative h-[300px] overflow-hidden rounded-lg shadow-sm">
                <img alt="Institutional campus view"
                     class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                     src="https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=800&q=80"/>
                <div class="absolute inset-0 flex flex-col justify-end bg-gradient-to-t from-black/80 via-transparent to-transparent p-lg">
                    <h4 class="text-label-md font-bold text-white">East Wing Completion</h4>
                    <p class="text-[10px] text-white/80">Infrastructure Update • June 2024</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom summary tables --}}
    <div class="mt-xl grid grid-cols-1 gap-xl lg:grid-cols-2">
        {{-- Best attendance classes --}}
        <div class="rounded-lg border border-outline-variant bg-surface-container-lowest shadow-sm">
            <div class="flex items-center justify-between border-b border-outline-variant bg-surface-container-low p-md">
                <h3 class="text-label-md font-bold text-on-surface">Best Attendance Classes</h3>
                <span class="text-[10px] font-bold text-tertiary">This Month</span>
            </div>
            <table class="w-full text-left text-body-md">
                <thead class="border-b border-outline-variant bg-surface-container-low/50">
                    <tr class="text-[10px] text-on-surface-variant">
                        <th class="px-lg py-3">Class / Semester</th>
                        <th class="px-lg py-3">Teacher</th>
                        <th class="px-lg py-3 text-right">Avg %</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container">
                    @foreach ($metrics['best_attendance'] as $row)
                        <tr>
                            <td class="px-lg py-3 font-bold">{{ $row['class'] }}</td>
                            <td class="px-lg py-3">{{ $row['teacher'] }}</td>
                            <td class="px-lg py-3 text-right font-bold text-tertiary">{{ $row['rate'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Highest dues --}}
        <div class="rounded-lg border border-outline-variant bg-surface-container-lowest shadow-sm">
            <div class="flex items-center justify-between border-b border-outline-variant bg-surface-container-low p-md">
                <h3 class="text-label-md font-bold text-on-surface">Students with Highest Dues</h3>
                <span class="text-[10px] font-bold text-error">Immediate Action</span>
            </div>
            <table class="w-full text-left text-body-md">
                <thead class="border-b border-outline-variant bg-surface-container-low/50">
                    <tr class="text-[10px] text-on-surface-variant">
                        <th class="px-lg py-3">Student Name</th>
                        <th class="px-lg py-3">Program</th>
                        <th class="px-lg py-3 text-right">Dues Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container">
                    @foreach ($metrics['highest_dues'] as $row)
                        <tr>
                            <td class="px-lg py-3 font-bold">{{ $row['name'] }}</td>
                            <td class="px-lg py-3">{{ $row['program'] }}</td>
                            <td class="px-lg py-3 text-right font-bold text-error">{{ format_money($row['amount']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
