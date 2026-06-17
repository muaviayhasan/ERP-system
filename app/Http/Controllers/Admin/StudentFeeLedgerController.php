<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeLedgerEntry;
use App\Models\Student;
use App\Models\StudentFeeAssignment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

/**
 * Student Fee Ledger — a read-only financial history per student, sourced from
 * the fee_ledger_entries table (the per-student source of truth).
 */
class StudentFeeLedgerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:fee-payments.view'),
        ];
    }

    public function index(Request $request): View
    {
        $query = StudentFeeAssignment::query()->with(['student', 'program']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->whereHas('student', fn ($s) => $s->where('full_name', 'like', "%{$term}%")->orWhere('student_code', 'like', "%{$term}%"));
        }

        return view('admin.student-fee-ledger.index', [
            'accounts' => $query->latest('id')->paginate(per_page())->withQueryString(),
        ]);
    }

    public function show(Student $student): View
    {
        $assignments = StudentFeeAssignment::where('student_id', $student->id)->with(['feeStructure', 'program'])->get();
        $entries = FeeLedgerEntry::where('student_id', $student->id)->orderBy('entry_date')->orderBy('id')->get();

        return view('admin.student-fee-ledger.show', [
            'student' => $student->load('program'),
            'assignments' => $assignments,
            'entries' => $entries,
            'summary' => [
                'payable' => (float) $assignments->sum('final_payable'),
                'paid' => (float) $assignments->sum('total_paid'),
                'pending' => (float) $assignments->sum('total_pending'),
                'scholarship' => (float) $assignments->sum('scholarship_amount'),
            ],
        ]);
    }
}
