<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreSemesterRequest;
use App\Http\Requests\Academic\UpdateSemesterRequest;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\Department;
use App\Models\Program;
use App\Models\Semester;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class SemesterController extends Controller implements HasMiddleware
{
    public const STATUSES = ['upcoming', 'active', 'completed'];

    public const GRADING_SYSTEMS = ['GPA 4.0', 'Percentage', 'Letter Grade'];

    private const BOOLEANS = ['generate_fee_plan', 'is_locked', 'fee_cycle_generated', 'exam_cycle_generated'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:semesters.view', only: ['index']),
            new Middleware('can:semesters.create', only: ['create', 'store']),
            new Middleware('can:semesters.edit', only: ['edit', 'update']),
            new Middleware('can:semesters.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Semester::query()->with(['program', 'department']);

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

        return view('admin.semesters.index', [
            'semesters' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create(): View
    {
        return view('admin.semesters.create', $this->options());
    }

    public function store(StoreSemesterRequest $request): RedirectResponse
    {
        Semester::create($this->payload($request));

        return redirect()->route('semesters.index')->with('status', 'Semester created successfully.');
    }

    public function edit(Semester $semester): View
    {
        return view('admin.semesters.edit', array_merge($this->options(), ['semester' => $semester]));
    }

    public function update(UpdateSemesterRequest $request, Semester $semester): RedirectResponse
    {
        $semester->update($this->payload($request));

        return redirect()->route('semesters.index')->with('status', 'Semester updated successfully.');
    }

    public function destroy(Semester $semester): RedirectResponse
    {
        $semester->delete();

        return back()->with('status', 'Semester deleted successfully.');
    }

    private function options(): array
    {
        return [
            'programs' => Program::orderBy('name')->get(['id', 'name']),
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(['id', 'name']),
        ];
    }

    /** @return array<string, mixed> */
    private function payload(FormRequest $request): array
    {
        return array_merge(
            $request->validated(),
            ['status' => $request->input('status') ?: 'upcoming'],
            collect(self::BOOLEANS)->mapWithKeys(fn ($b) => [$b => $request->boolean($b)])->all(),
        );
    }
}
