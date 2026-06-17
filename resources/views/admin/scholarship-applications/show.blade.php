@extends('layouts.admin')

@section('title', 'Application Review')

@php
    $statusStyles = [
        'pending' => 'bg-orange-100 text-orange-600', 'under_review' => 'bg-primary/10 text-primary',
        'approved' => 'bg-tertiary/10 text-tertiary', 'rejected' => 'bg-error/10 text-error',
        'changes_requested' => 'bg-secondary-container text-on-secondary-container',
    ];
    $checks = [
        'GPA Requirement' => $application->gpa_check_passed,
        'Policy Compliance' => $application->policy_compliance_passed,
        'No Duplicate Aid' => $application->no_duplicate_passed,
    ];
@endphp

@section('content')
    <div class="mb-lg flex items-center gap-3">
        <a href="{{ route('scholarship-applications.index') }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container-low"><span class="material-symbols-outlined">arrow_back</span></a>
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Application Review</h2>
            <p class="text-body-md text-on-surface-variant">{{ $application->student?->full_name }} · {{ $application->program?->name }}</p>
        </div>
        <span class="ml-auto rounded-full px-3 py-1 text-label-sm font-bold {{ $statusStyles[$application->status] ?? '' }}">{{ Str::headline($application->status) }}</span>
    </div>

    <div class="grid grid-cols-1 gap-lg lg:grid-cols-3">
        {{-- Details --}}
        <div class="space-y-lg lg:col-span-2">
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <h3 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">Application Details</h3>
                <dl class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    @foreach ([
                        'Student' => $application->student?->full_name,
                        'Scholarship' => $application->scholarship?->name,
                        'Type' => $application->type,
                        'Priority' => Str::headline($application->priority ?? 'normal'),
                        'CGPA' => $application->cgpa,
                        'Documents' => $application->documents_count,
                        'Applied' => $application->application_date ? format_date($application->application_date) : null,
                    ] as $label => $value)
                        <div class="flex justify-between border-b border-outline-variant/50 pb-2">
                            <dt class="text-label-sm text-on-surface-variant">{{ $label }}</dt>
                            <dd class="font-medium text-on-surface">{{ $value ?: '—' }}</dd>
                        </div>
                    @endforeach
                </dl>
                @if ($application->reason)
                    <div class="mt-md rounded-lg bg-surface-container-low p-md">
                        <p class="text-label-sm font-bold text-on-surface-variant">Reason</p>
                        <p class="text-body-md text-on-surface">{{ $application->reason }}</p>
                    </div>
                @endif
            </div>

            {{-- Financial impact --}}
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <h3 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">Financial Impact</h3>
                <div class="grid grid-cols-1 gap-md sm:grid-cols-3">
                    <div class="rounded-lg border border-outline-variant p-md"><p class="text-label-sm text-on-surface-variant">Original Fee</p><p class="font-bold text-on-surface">{{ format_money($application->original_fee) }}</p></div>
                    <div class="rounded-lg border border-outline-variant p-md"><p class="text-label-sm text-on-surface-variant">Requested Discount</p><p class="font-bold text-tertiary">{{ $application->requested_value ? format_money($application->requested_value) : ($application->requested_discount_percent.'%') }}</p></div>
                    <div class="rounded-lg border border-outline-variant bg-tertiary/5 p-md"><p class="text-label-sm text-on-surface-variant">Final Payable</p><p class="font-bold text-on-surface">{{ format_money($application->final_payable ?? max(($application->original_fee ?? 0) - ($application->requested_value ?? 0), 0)) }}</p></div>
                </div>
            </div>

            {{-- Eligibility --}}
            <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                <h3 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">Auto-Eligibility Check</h3>
                <div class="space-y-2">
                    @foreach ($checks as $label => $passed)
                        <div class="flex items-center justify-between rounded-lg border border-outline-variant px-3 py-2">
                            <span class="text-body-md text-on-surface">{{ $label }}</span>
                            @if ($passed)
                                <span class="inline-flex items-center gap-1 text-label-md font-bold text-tertiary"><span class="material-symbols-outlined text-[18px]">check_circle</span> Pass</span>
                            @else
                                <span class="inline-flex items-center gap-1 text-label-md font-bold text-on-surface-variant"><span class="material-symbols-outlined text-[18px]">remove_circle</span> Not verified</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Decision log --}}
            @if ($application->logs->isNotEmpty())
                <div class="rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm">
                    <h3 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">History</h3>
                    <div class="space-y-3">
                        @foreach ($application->logs->sortByDesc('performed_at') as $log)
                            <div class="flex gap-3">
                                <div class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary"><span class="material-symbols-outlined text-[16px]">history</span></div>
                                <div>
                                    <p class="text-body-md text-on-surface"><span class="font-bold">{{ Str::headline($log->action) }}</span> @if ($log->status) → {{ Str::headline($log->status) }} @endif</p>
                                    <p class="text-label-sm text-on-surface-variant">{{ $log->performedBy?->name ?? 'System' }} · {{ format_datetime($log->performed_at) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Decision panel --}}
        <aside>
            @can('scholarship-applications.edit')
                <form method="POST" action="{{ route('scholarship-applications.decide', $application) }}"
                      class="sticky top-20 rounded-xl border border-outline-variant bg-surface-container-lowest p-lg shadow-sm" x-data="{ status: '' }">
                    @csrf
                    <h3 class="mb-md text-label-md font-bold uppercase tracking-widest text-primary">Decision</h3>
                    <input type="hidden" name="status" :value="status"/>
                    <div class="space-y-2">
                        <button type="submit" @click="status='approved'" class="flex w-full items-center justify-center gap-2 rounded-lg bg-tertiary px-lg py-2.5 font-bold text-on-tertiary hover:opacity-90"><span class="material-symbols-outlined text-[18px]">check_circle</span> Approve &amp; Grant</button>
                        <button type="submit" @click="status='under_review'" class="flex w-full items-center justify-center gap-2 rounded-lg border border-outline-variant px-lg py-2.5 font-bold text-on-surface-variant hover:bg-surface-container-low"><span class="material-symbols-outlined text-[18px]">visibility</span> Mark Under Review</button>
                        <button type="submit" @click="status='changes_requested'" class="flex w-full items-center justify-center gap-2 rounded-lg border border-outline-variant px-lg py-2.5 font-bold text-orange-600 hover:bg-orange-50"><span class="material-symbols-outlined text-[18px]">edit_note</span> Request Changes</button>
                        <button type="submit" @click="status='rejected'" class="flex w-full items-center justify-center gap-2 rounded-lg border border-error/30 px-lg py-2.5 font-bold text-error hover:bg-error/10"><span class="material-symbols-outlined text-[18px]">cancel</span> Reject</button>
                    </div>
                    <div class="mt-md space-y-1">
                        <label class="text-label-sm font-bold text-on-surface-variant">Decision Notes</label>
                        <textarea name="decision_notes" rows="3" placeholder="Add notes for this decision..."
                                  class="w-full rounded-lg border border-outline-variant bg-white px-md py-2.5 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/20">{{ old('decision_notes', $application->decision_notes) }}</textarea>
                    </div>
                    <p class="mt-2 text-label-sm text-on-surface-variant">Approving grants a scholarship assignment automatically.</p>
                </form>
            @endcan
        </aside>
    </div>
@endsection
