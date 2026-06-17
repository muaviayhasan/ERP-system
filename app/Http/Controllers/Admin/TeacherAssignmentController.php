<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StoreTeacherAssignmentRequest;
use App\Http\Requests\Hr\UpdateTeacherAssignmentRequest;
use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class TeacherAssignmentController extends Controller implements HasMiddleware
{
    public const TIMETABLE_STATUSES = ['pending', 'scheduled', 'published'];

    public const STATUSES = ['active', 'inactive'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:teacher-assignments.view', only: ['index']),
            new Middleware('can:teacher-assignments.create', only: ['create', 'store']),
            new Middleware('can:teacher-assignments.edit', only: ['edit', 'update']),
            new Middleware('can:teacher-assignments.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = TeacherAssignment::query()->with(['teacher', 'department', 'program', 'subject', 'course']);

        if ($request->filled('teacher')) {
            $query->where('teacher_id', $request->input('teacher'));
        }
        if ($request->filled('department')) {
            $query->where('department_id', $request->input('department'));
        }
        if ($request->filled('timetable_status')) {
            $query->where('timetable_status', $request->input('timetable_status'));
        }

        return view('admin.teacher-assignments.index', [
            'assignments' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'teachers' => Teacher::orderBy('full_name')->get(['id', 'full_name', 'teacher_code']),
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'stats' => [
                'total' => TeacherAssignment::count(),
                'published' => TeacherAssignment::where('timetable_status', 'published')->count(),
                'pending' => TeacherAssignment::where('timetable_status', 'pending')->count(),
                'conflicts' => TeacherAssignment::where('has_conflict', true)->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.teacher-assignments.create', $this->options());
    }

    public function store(StoreTeacherAssignmentRequest $request): RedirectResponse
    {
        TeacherAssignment::create($this->payload($request));

        return redirect()->route('teacher-assignments.index')->with('status', 'Assignment created successfully.');
    }

    public function edit(TeacherAssignment $teacherAssignment): View
    {
        return view('admin.teacher-assignments.edit', array_merge($this->options(), [
            'assignment' => $teacherAssignment,
        ]));
    }

    public function update(UpdateTeacherAssignmentRequest $request, TeacherAssignment $teacherAssignment): RedirectResponse
    {
        $teacherAssignment->update($this->payload($request));

        return redirect()->route('teacher-assignments.index')->with('status', 'Assignment updated successfully.');
    }

    public function destroy(TeacherAssignment $teacherAssignment): RedirectResponse
    {
        $teacherAssignment->delete();

        return back()->with('status', 'Assignment deleted successfully.');
    }

    private function options(): array
    {
        return [
            'teachers' => Teacher::orderBy('full_name')->get(['id', 'full_name', 'teacher_code']),
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
            'courses' => Course::orderBy('name')->get(['id', 'name']),
            'subjects' => Subject::orderBy('name')->get(['id', 'name']),
            'sections' => Section::orderBy('name')->get(['id', 'name']),
            'semesters' => Semester::orderBy('name')->get(['id', 'name']),
        ];
    }

    /** @return array<string, mixed> */
    private function payload(FormRequest $request): array
    {
        return array_merge(
            $request->validated(),
            [
                'status' => $request->input('status') ?: 'active',
                'timetable_status' => $request->input('timetable_status') ?: 'pending',
                'has_conflict' => $request->boolean('has_conflict'),
            ],
        );
    }
}
