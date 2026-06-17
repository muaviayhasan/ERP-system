<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreCourseRequest;
use App\Http\Requests\Academic\UpdateCourseRequest;
use App\Models\Campus;
use App\Models\Course;
use App\Models\Department;
use App\Models\Program;
use App\Models\Semester;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class CourseController extends Controller implements HasMiddleware
{
    public const TYPES = ['Core', 'Elective', 'Lab', 'General'];

    public const STATUSES = ['active', 'inactive'];

    private const BOOLEANS = ['is_active', 'open_enrollment'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:courses.view', only: ['index']),
            new Middleware('can:courses.create', only: ['create', 'store']),
            new Middleware('can:courses.edit', only: ['edit', 'update']),
            new Middleware('can:courses.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Course::query()->with(['program', 'semester']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%"));
        }
        if ($request->filled('program')) {
            $query->where('program_id', $request->input('program'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.courses.index', [
            'courses' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create(): View
    {
        return view('admin.courses.create', $this->options());
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        Course::create($this->payload($request));

        return redirect()->route('courses.index')->with('status', 'Course created successfully.');
    }

    public function edit(Course $course): View
    {
        return view('admin.courses.edit', array_merge($this->options(), ['course' => $course]));
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($this->payload($request));

        return redirect()->route('courses.index')->with('status', 'Course updated successfully.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();

        return back()->with('status', 'Course deleted successfully.');
    }

    private function options(): array
    {
        return [
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'semesters' => Semester::orderBy('name')->get(['id', 'name']),
        ];
    }

    /** @return array<string, mixed> */
    private function payload(FormRequest $request): array
    {
        return array_merge(
            $request->validated(),
            ['status' => $request->input('status') ?: 'active'],
            collect(self::BOOLEANS)->mapWithKeys(fn ($b) => [$b => $request->boolean($b)])->all(),
        );
    }
}
