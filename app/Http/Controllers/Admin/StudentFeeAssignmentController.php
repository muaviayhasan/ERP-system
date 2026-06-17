<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fee\StoreStudentFeeAssignmentRequest;
use App\Http\Requests\Fee\UpdateStudentFeeAssignmentRequest;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\FeePlan;
use App\Models\FeeStructure;
use App\Models\Program;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentFeeAssignment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class StudentFeeAssignmentController extends Controller implements HasMiddleware
{
    public const STATUSES = ['pending', 'active', 'partial', 'paid', 'hold'];

    private const BOOLEANS = ['late_fee_enabled', 'email_notifications_enabled'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:student-fee-assignments.view', only: ['index']),
            new Middleware('can:student-fee-assignments.create', only: ['create', 'store']),
            new Middleware('can:student-fee-assignments.edit', only: ['edit', 'update']),
            new Middleware('can:student-fee-assignments.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = StudentFeeAssignment::query()->with(['student', 'feeStructure', 'feePlan', 'program']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->whereHas('student', fn ($s) => $s->where('full_name', 'like', "%{$term}%")->orWhere('student_code', 'like', "%{$term}%"));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.student-fee-assignments.index', [
            'assignments' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'stats' => [
                'total' => StudentFeeAssignment::count(),
                'payable' => (float) StudentFeeAssignment::sum('final_payable'),
                'collected' => (float) StudentFeeAssignment::sum('total_paid'),
                'pending' => (float) StudentFeeAssignment::sum('total_pending'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.student-fee-assignments.create', $this->options());
    }

    public function store(StoreStudentFeeAssignmentRequest $request): RedirectResponse
    {
        StudentFeeAssignment::create($this->payload($request));

        return redirect()->route('student-fee-assignments.index')->with('status', 'Fee assignment created successfully.');
    }

    public function edit(StudentFeeAssignment $studentFeeAssignment): View
    {
        return view('admin.student-fee-assignments.edit', array_merge($this->options(), [
            'assignment' => $studentFeeAssignment,
        ]));
    }

    public function update(UpdateStudentFeeAssignmentRequest $request, StudentFeeAssignment $studentFeeAssignment): RedirectResponse
    {
        $studentFeeAssignment->update($this->payload($request, $studentFeeAssignment));

        return redirect()->route('student-fee-assignments.index')->with('status', 'Fee assignment updated successfully.');
    }

    public function destroy(StudentFeeAssignment $studentFeeAssignment): RedirectResponse
    {
        $studentFeeAssignment->delete();

        return back()->with('status', 'Fee assignment deleted successfully.');
    }

    private function options(): array
    {
        return [
            'students' => Student::orderBy('full_name')->get(['id', 'full_name', 'student_code']),
            'structures' => FeeStructure::orderBy('name')->get(['id', 'name', 'total_fee']),
            'plans' => FeePlan::orderBy('name')->get(['id', 'name']),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
            'semesters' => Semester::orderBy('name')->get(['id', 'name']),
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(['id', 'name']),
        ];
    }

    /**
     * Derive final_payable (fee − scholarship) and total_pending (payable − paid)
     * so the money columns are always internally consistent.
     *
     * @return array<string, mixed>
     */
    private function payload(FormRequest $request, ?StudentFeeAssignment $existing = null): array
    {
        $data = $request->validated();

        $totalFee = round((float) ($data['total_fee'] ?? $existing?->total_fee ?? 0), 2);
        $scholarship = round((float) ($data['scholarship_amount'] ?? $existing?->scholarship_amount ?? 0), 2);
        $paid = round((float) ($data['total_paid'] ?? $existing?->total_paid ?? 0), 2);

        $finalPayable = max($totalFee - $scholarship, 0);

        $data['final_payable'] = $finalPayable;
        $data['total_pending'] = max($finalPayable - $paid, 0);
        $data['status'] = $request->input('status') ?: 'pending';

        foreach (self::BOOLEANS as $b) {
            $data[$b] = $request->boolean($b);
        }

        return $data;
    }
}
