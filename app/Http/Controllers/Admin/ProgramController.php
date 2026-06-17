<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreProgramRequest;
use App\Http\Requests\Academic\UpdateProgramRequest;
use App\Models\Campus;
use App\Models\Department;
use App\Models\Program;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class ProgramController extends Controller implements HasMiddleware
{
    public const DEGREE_LEVELS = ['Bachelor', 'Master', 'PhD', 'Associate', 'Diploma'];

    public const STATUSES = ['active', 'inactive', 'pending'];

    private const BOOLEANS = ['multi_department_access', 'allow_admissions', 'lock_structure'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:programs.view', only: ['index']),
            new Middleware('can:programs.create', only: ['create', 'store']),
            new Middleware('can:programs.edit', only: ['edit', 'update']),
            new Middleware('can:programs.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Program::query()->with('department')->withCount('courses');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%"));
        }
        if ($request->filled('degree_level')) {
            $query->where('degree_level', $request->input('degree_level'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.programs.index', [
            'programs' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'stats' => [
                'total' => Program::count(),
                'active' => Program::where('status', 'active')->count(),
                'departments' => Department::count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.programs.create', $this->options());
    }

    public function store(StoreProgramRequest $request): RedirectResponse
    {
        $program = Program::create($this->payload($request));
        $program->campuses()->sync($this->campusIds($request));

        return redirect()->route('programs.index')->with('status', 'Program created successfully.');
    }

    public function edit(Program $program): View
    {
        return view('admin.programs.edit', array_merge($this->options(), [
            'program' => $program->load('campuses'),
        ]));
    }

    public function update(UpdateProgramRequest $request, Program $program): RedirectResponse
    {
        $program->update($this->payload($request));
        $program->campuses()->sync($this->campusIds($request));

        return redirect()->route('programs.index')->with('status', 'Program updated successfully.');
    }

    public function destroy(Program $program): RedirectResponse
    {
        $program->delete();

        return back()->with('status', 'Program deleted successfully.');
    }

    private function options(): array
    {
        return [
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'users' => User::orderBy('name')->get(['id', 'name']),
        ];
    }

    /** @return array<string, mixed> */
    private function payload(FormRequest $request): array
    {
        return array_merge(
            Arr::except($request->validated(), ['campuses']),
            ['status' => $request->input('status') ?: 'active'],
            collect(self::BOOLEANS)->mapWithKeys(fn ($b) => [$b => $request->boolean($b)])->all(),
        );
    }

    /** @return array<int> */
    private function campusIds(FormRequest $request): array
    {
        return array_map('intval', (array) ($request->validated()['campuses'] ?? []));
    }
}
