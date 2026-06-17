<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreDepartmentRequest;
use App\Http\Requests\Academic\UpdateDepartmentRequest;
use App\Models\Campus;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class DepartmentController extends Controller implements HasMiddleware
{
    public const TYPES = ['University', 'College', 'School', 'Vocational'];

    private const BOOLEANS = ['semester_system', 'credit_hour_system', 'is_active', 'allow_admissions'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:departments.view', only: ['index']),
            new Middleware('can:departments.create', only: ['create', 'store']),
            new Middleware('can:departments.edit', only: ['edit', 'update']),
            new Middleware('can:departments.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Department::query()->with('campuses')->withCount('programs');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%"));
        }
        if ($request->filled('type')) {
            $query->where('institution_type', $request->input('type'));
        }

        return view('admin.departments.index', [
            'departments' => $query->latest('id')->paginate(per_page())->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.departments.create', $this->options());
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $department = Department::create($this->payload($request));
        $department->campuses()->sync($this->campusIds($request));

        return redirect()->route('departments.index')->with('status', 'Department created successfully.');
    }

    public function edit(Department $department): View
    {
        return view('admin.departments.edit', array_merge($this->options(), [
            'department' => $department->load('campuses'),
        ]));
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($this->payload($request));
        $department->campuses()->sync($this->campusIds($request));

        return redirect()->route('departments.index')->with('status', 'Department updated successfully.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $department->delete();

        return back()->with('status', 'Department deleted successfully.');
    }

    private function options(): array
    {
        return [
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'users' => User::orderBy('name')->get(['id', 'name']),
        ];
    }

    /** @return array<string, mixed> */
    private function payload(FormRequest $request): array
    {
        return array_merge(
            Arr::except($request->validated(), ['campuses']),
            collect(self::BOOLEANS)->mapWithKeys(fn ($b) => [$b => $request->boolean($b)])->all(),
        );
    }

    /** @return array<int> */
    private function campusIds(FormRequest $request): array
    {
        return array_map('intval', (array) ($request->validated()['campuses'] ?? []));
    }
}
