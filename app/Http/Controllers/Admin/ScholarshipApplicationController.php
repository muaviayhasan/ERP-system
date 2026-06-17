<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Scholarship\StoreScholarshipApplicationRequest;
use App\Models\Program;
use App\Models\Scholarship;
use App\Models\ScholarshipApplication;
use App\Models\ScholarshipApplicationLog;
use App\Models\ScholarshipAssignment;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ScholarshipApplicationController extends Controller implements HasMiddleware
{
    public const STATUSES = ['pending', 'under_review', 'approved', 'rejected', 'changes_requested'];

    public const PRIORITIES = ['normal', 'high', 'urgent'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:scholarship-applications.view', only: ['index', 'show']),
            new Middleware('can:scholarship-applications.create', only: ['create', 'store']),
            new Middleware('can:scholarship-applications.edit', only: ['decide']),
            new Middleware('can:scholarship-applications.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = ScholarshipApplication::query()->with(['student', 'scholarship', 'program']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->whereHas('student', fn ($s) => $s->where('full_name', 'like', "%{$term}%")->orWhere('student_code', 'like', "%{$term}%"));
        }
        if ($request->filled('status') && in_array($request->input('status'), self::STATUSES, true)) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $counts = ScholarshipApplication::select('status', DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status');

        return view('admin.scholarship-applications.index', [
            'applications' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'stats' => [
                'pending' => ($counts['pending'] ?? 0) + ($counts['under_review'] ?? 0),
                'approved' => $counts['approved'] ?? 0,
                'rejected' => $counts['rejected'] ?? 0,
                'value' => (float) ScholarshipApplication::where('status', 'approved')->sum('requested_value'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.scholarship-applications.create', $this->options());
    }

    public function store(StoreScholarshipApplicationRequest $request): RedirectResponse
    {
        $application = ScholarshipApplication::create(array_merge($request->validated(), [
            'status' => $request->input('status') ?: 'pending',
        ]));

        $this->log($application, 'submitted', $application->status, $request->user()->id);

        return redirect()->route('scholarship-applications.index')->with('status', 'Application submitted successfully.');
    }

    public function show(ScholarshipApplication $scholarshipApplication): View
    {
        return view('admin.scholarship-applications.show', [
            'application' => $scholarshipApplication->load(['student', 'scholarship', 'program', 'semester', 'reviewedBy', 'logs.performedBy']),
        ]);
    }

    /**
     * Record a review decision. Approving grants a ScholarshipAssignment (the aid
     * record); every decision is journalled to scholarship_application_logs.
     */
    public function decide(Request $request, ScholarshipApplication $scholarshipApplication): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:under_review,approved,rejected,changes_requested'],
            'decision_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($data, $scholarshipApplication, $request) {
            $scholarshipApplication->update([
                'status' => $data['status'],
                'decision_notes' => $data['decision_notes'] ?? null,
                'reviewed_by' => $request->user()->id,
            ]);

            if ($data['status'] === 'approved') {
                ScholarshipAssignment::firstOrCreate(
                    ['student_id' => $scholarshipApplication->student_id, 'scholarship_id' => $scholarshipApplication->scholarship_id],
                    [
                        'discount_amount' => $scholarshipApplication->requested_value
                            ?? $this->discountFromScholarship($scholarshipApplication),
                        'status' => 'active',
                        'assigned_by' => $request->user()->id,
                    ],
                );
            }

            $this->log($scholarshipApplication, 'decision', $data['status'], $request->user()->id);
        });

        return redirect()->route('scholarship-applications.show', $scholarshipApplication)
            ->with('status', 'Decision recorded: '.str($data['status'])->headline().'.');
    }

    public function destroy(ScholarshipApplication $scholarshipApplication): RedirectResponse
    {
        $scholarshipApplication->delete();

        return redirect()->route('scholarship-applications.index')->with('status', 'Application deleted successfully.');
    }

    private function options(): array
    {
        return [
            'students' => Student::orderBy('full_name')->get(['id', 'full_name', 'student_code']),
            'scholarships' => Scholarship::orderBy('name')->get(['id', 'name', 'type']),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
            'semesters' => Semester::orderBy('name')->get(['id', 'name']),
        ];
    }

    /** Derive a discount when the application didn't request an explicit value. */
    private function discountFromScholarship(ScholarshipApplication $application): float
    {
        $scholarship = $application->scholarship;
        if (! $scholarship) {
            return 0.0;
        }
        if ($scholarship->value_type === 'percentage') {
            return round((float) ($application->original_fee ?? 0) * (float) $scholarship->value / 100, 2);
        }

        return round((float) $scholarship->value, 2);
    }

    private function log(ScholarshipApplication $application, string $action, ?string $status, int $userId): void
    {
        ScholarshipApplicationLog::create([
            'scholarship_application_id' => $application->id,
            'action' => $action,
            'status' => $status,
            'performed_by' => $userId,
            'performed_at' => now(),
        ]);
    }
}
