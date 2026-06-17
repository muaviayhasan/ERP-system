@extends('layouts.admin')

@section('title', 'Student Documents')

@php
    use App\Http\Controllers\Admin\StudentDocumentController;
    $statusStyles = [
        'verified' => 'bg-tertiary/10 text-tertiary',
        'pending' => 'bg-orange-100 text-orange-600',
        'rejected' => 'bg-error/10 text-error',
    ];
@endphp

@section('content')
    <div class="mb-lg flex flex-col justify-between gap-md md:flex-row md:items-end">
        <div>
            <h2 class="font-headline-lg text-headline-lg text-on-surface">Student Documents</h2>
            <p class="text-body-md text-on-surface-variant">Manage and verify student documents and records.</p>
        </div>
        @can('students.create')
            <a href="{{ route('student-documents.create') }}"
               class="flex items-center gap-xs rounded-lg bg-primary px-lg py-2.5 font-bold text-on-primary transition-all hover:shadow-lg hover:shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined">upload_file</span> Upload Document
            </a>
        @endcan
    </div>

    {{-- Stats --}}
    <div class="mb-lg grid grid-cols-1 gap-md md:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['Total Documents', $stats['total'], 'folder', 'bg-primary/10 text-primary'],
            ['Verified', $stats['verified'], 'verified', 'bg-tertiary/10 text-tertiary'],
            ['Pending', $stats['pending'], 'hourglass_top', 'bg-orange-100 text-orange-600'],
            ['Rejected', $stats['rejected'], 'cancel', 'bg-error/10 text-error'],
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
    <form method="GET" class="mb-lg grid grid-cols-1 gap-md md:grid-cols-5">
        <div class="relative md:col-span-2">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student or document..."
                   class="w-full rounded-xl border border-outline-variant bg-white py-2.5 pl-10 pr-4 text-body-md outline-none focus:border-primary focus:ring-2 focus:ring-primary/10"/>
        </div>
        <select name="status" data-allow-clear placeholder="All Statuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Statuses</option>
            @foreach (StudentDocumentController::STATUSES as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ ucfirst($st) }}</option>
            @endforeach
        </select>
        <select name="type" data-allow-clear placeholder="All Types"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Types</option>
            @foreach (StudentDocumentController::TYPES as $t)
                <option value="{{ $t }}" @selected(request('type') === $t)>{{ $t }}</option>
            @endforeach
        </select>
        <select name="campus" data-allow-clear placeholder="All Campuses"
                class="rounded-xl border border-outline-variant bg-white px-4 py-2.5 text-body-md outline-none focus:border-primary">
            <option value="">All Campuses</option>
            @foreach ($campuses as $campus)
                <option value="{{ $campus->id }}" @selected((int) request('campus') === $campus->id)>{{ $campus->name }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-hidden rounded-xl border border-outline-variant bg-surface-container-lowest shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead class="border-b border-outline-variant bg-surface-container-low">
                    <tr class="text-label-sm uppercase tracking-wider text-on-surface-variant">
                        <th class="px-lg py-4 font-bold">Student</th>
                        <th class="px-lg py-4 font-bold">Type</th>
                        <th class="px-lg py-4 font-bold">Title</th>
                        <th class="px-lg py-4 font-bold">Status</th>
                        <th class="px-lg py-4 font-bold">Uploaded</th>
                        <th class="px-lg py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse ($documents as $doc)
                        <tr class="transition-colors hover:bg-surface-container-low/40">
                            <td class="px-lg py-3">
                                <p class="font-bold text-on-surface">{{ $doc->student?->full_name ?? '—' }}</p>
                                <p class="text-label-sm text-on-surface-variant">{{ $doc->student?->student_code }}</p>
                            </td>
                            <td class="px-lg py-3">
                                <span class="rounded-md bg-secondary-container px-2 py-1 text-label-sm text-on-secondary-container">{{ $doc->document_type }}</span>
                            </td>
                            <td class="px-lg py-3">
                                @if ($doc->file_path)
                                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-on-surface hover:text-primary hover:underline">{{ $doc->title }}</a>
                                @else
                                    <span class="text-on-surface">{{ $doc->title }}</span>
                                @endif
                            </td>
                            <td class="px-lg py-3">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-label-sm font-bold {{ $statusStyles[$doc->status] ?? $statusStyles['pending'] }}">{{ ucfirst($doc->status) }}</span>
                            </td>
                            <td class="px-lg py-3 text-label-md text-on-surface-variant">
                                {{ $doc->uploaded_at ? format_date($doc->uploaded_at) : '—' }}
                                <div class="text-label-sm">{{ $doc->uploaded_by }}</div>
                            </td>
                            <td class="px-lg py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('students.edit')
                                        <a href="{{ route('student-documents.edit', $doc) }}" class="rounded-lg p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary" title="Verify / edit">
                                            <span class="material-symbols-outlined text-[20px]">fact_check</span>
                                        </a>
                                    @endcan
                                    @can('students.delete')
                                        <form method="POST" action="{{ route('student-documents.destroy', $doc) }}" onsubmit="return confirm('Delete this document?');">
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
                            <td colspan="6" class="px-lg py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined mb-2 block text-[40px] opacity-40">folder_off</span>
                                No documents found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-outline-variant bg-surface-container-low px-lg py-3">
            {{ $documents->links() }}
        </div>
    </div>
@endsection
