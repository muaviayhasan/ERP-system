<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Scholarship\StoreScholarshipAssignmentRequest;
use App\Models\ScholarshipAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class ScholarshipAssignmentController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:scholarship-assignments.create', only: ['create', 'store']),
            new Middleware('can:scholarship-assignments.delete', only: ['destroy']),
        ];
    }

    public function create(): View
    {
        return view('admin.scholarships.assign', [
            'students' => \App\Models\Student::orderBy('full_name')->get(['id', 'full_name', 'student_code']),
            'scholarships' => \App\Models\Scholarship::where('status', 'active')->orderBy('name')->get(['id', 'name', 'value', 'value_type']),
        ]);
    }

    public function store(StoreScholarshipAssignmentRequest $request): RedirectResponse
    {
        ScholarshipAssignment::create(array_merge($request->validated(), [
            'status' => $request->input('status') ?: 'active',
            'assigned_by' => $request->user()->id,
        ]));

        return redirect()->route('scholarships.index')->with('status', 'Scholarship assigned successfully.');
    }

    public function destroy(ScholarshipAssignment $scholarshipAssignment): RedirectResponse
    {
        $scholarshipAssignment->delete();

        return back()->with('status', 'Scholarship assignment removed.');
    }
}
