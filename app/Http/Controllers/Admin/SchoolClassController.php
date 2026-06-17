<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreSchoolClassRequest;
use App\Http\Requests\Academic\UpdateSchoolClassRequest;
use App\Models\Campus;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class SchoolClassController extends Controller implements HasMiddleware
{
    public const TYPES = ['University', 'College', 'School', 'Vocational'];

    public const LEVELS = ['Primary', 'Secondary', 'Higher Secondary', 'Undergraduate', 'Postgraduate', 'Doctorate'];

    public const STATUSES = ['active', 'inactive'];

    private const BOOLEANS = ['multi_campus_sharing', 'is_active', 'allow_admissions'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:classes.view', only: ['index']),
            new Middleware('can:classes.create', only: ['create', 'store']),
            new Middleware('can:classes.edit', only: ['edit', 'update']),
            new Middleware('can:classes.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = SchoolClass::query()->with('campus')->withCount('sections');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%"));
        }
        if ($request->filled('level')) {
            $query->where('academic_level', $request->input('level'));
        }
        if ($request->filled('campus')) {
            $query->where('campus_id', $request->input('campus'));
        }

        return view('admin.classes.index', [
            'classes' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create(): View
    {
        return view('admin.classes.create', $this->options());
    }

    public function store(StoreSchoolClassRequest $request): RedirectResponse
    {
        SchoolClass::create($this->payload($request));

        return redirect()->route('classes.index')->with('status', 'Class created successfully.');
    }

    public function edit(SchoolClass $class): View
    {
        return view('admin.classes.edit', array_merge($this->options(), ['schoolClass' => $class]));
    }

    public function update(UpdateSchoolClassRequest $request, SchoolClass $class): RedirectResponse
    {
        $class->update($this->payload($request));

        return redirect()->route('classes.index')->with('status', 'Class updated successfully.');
    }

    public function destroy(SchoolClass $class): RedirectResponse
    {
        $class->delete();

        return back()->with('status', 'Class deleted successfully.');
    }

    private function options(): array
    {
        return [
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'semesters' => Semester::orderBy('name')->get(['id', 'name']),
            'users' => User::orderBy('name')->get(['id', 'name']),
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
