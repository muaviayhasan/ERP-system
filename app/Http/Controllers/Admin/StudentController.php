<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Campus;
use App\Models\Program;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StudentController extends Controller implements HasMiddleware
{
    public const STATUSES = ['active', 'inactive'];

    public const ADMISSION_STATUSES = ['draft', 'submitted', 'enrolled'];

    public const GENDERS = ['male', 'female', 'other'];

    public const INSTITUTE_TYPES = ['School', 'College', 'University', 'Academy'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:students.view', only: ['index', 'show']),
            new Middleware('can:students.create', only: ['create', 'store']),
            new Middleware('can:students.edit', only: ['edit', 'update']),
            new Middleware('can:students.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Student::query()->with(['program', 'campus', 'section']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q
                ->where('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhere('full_name', 'like', "%{$term}%")
                ->orWhere('student_code', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%"));
        }
        if ($request->filled('program')) {
            $query->where('program_id', $request->input('program'));
        }
        if ($request->filled('campus')) {
            $query->where('campus_id', $request->input('campus'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.students.index', [
            'students' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'stats' => [
                'total' => Student::count(),
                'active' => Student::where('status', 'active')->count(),
                'enrolled' => Student::where('admission_status', 'enrolled')->count(),
                'draft' => Student::where('admission_status', 'draft')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.students.create', $this->options());
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $student = Student::create($this->payload($request));

        return redirect()->route('students.show', $student)->with('status', 'Student admitted successfully.');
    }

    public function show(Student $student): View
    {
        $student->load([
            'program', 'campus', 'academicYear', 'currentSemester', 'section', 'batch', 'advisor',
            'guardians', 'documents' => fn ($q) => $q->latest('id')->limit(8),
            'activities' => fn ($q) => $q->latest('activity_date')->limit(6),
        ]);

        return view('admin.students.show', ['student' => $student]);
    }

    public function edit(Student $student): View
    {
        return view('admin.students.edit', array_merge($this->options(), ['student' => $student]));
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $student->update($this->payload($request, $student));

        return redirect()->route('students.show', $student)->with('status', 'Student updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return redirect()->route('students.index')->with('status', 'Student deleted successfully.');
    }

    /** FK option collections shared by the admission/edit forms. */
    private function options(): array
    {
        return [
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(['id', 'name']),
            'semesters' => Semester::orderBy('name')->get(['id', 'name']),
            'sections' => Section::orderBy('name')->get(['id', 'name']),
            'classes' => SchoolClass::orderBy('name')->get(['id', 'name']),
            'batches' => Batch::orderBy('name')->get(['id', 'name']),
        ];
    }

    /**
     * Validated data with a derived full_name and an optional photo upload.
     *
     * @return array<string, mixed>
     */
    private function payload(FormRequest $request, ?Student $student = null): array
    {
        $data = $request->validated();

        $data['full_name'] = trim(($data['first_name'] ?? $student?->first_name ?? '')
            .' '.($data['last_name'] ?? $student?->last_name ?? '')) ?: ($data['full_name'] ?? null);

        if ($request->hasFile('photo')) {
            if ($student?->photo_url) {
                Storage::disk('public')->delete($student->photo_url);
            }
            $data['photo_url'] = $request->file('photo')->store('students', 'public');
        }

        return $data;
    }
}
