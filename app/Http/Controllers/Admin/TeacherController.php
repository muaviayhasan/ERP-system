<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StoreTeacherRequest;
use App\Http\Requests\Hr\UpdateTeacherRequest;
use App\Models\Campus;
use App\Models\Department;
use App\Models\Program;
use App\Models\Teacher;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TeacherController extends Controller implements HasMiddleware
{
    public const STATUSES = ['active', 'inactive'];

    public const INSTITUTE_TYPES = ['School', 'College', 'University', 'Academy'];

    public const DESIGNATIONS = [
        'Professor', 'Associate Professor', 'Assistant Professor',
        'Senior Lecturer', 'Lecturer', 'Lab Instructor', 'Head of Department',
    ];

    public static function middleware(): array
    {
        return [
            new Middleware('can:teachers.view', only: ['index', 'show']),
            new Middleware('can:teachers.create', only: ['create', 'store']),
            new Middleware('can:teachers.edit', only: ['edit', 'update']),
            new Middleware('can:teachers.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Teacher::query()->with(['department', 'campus', 'programs'])->withCount('assignments');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q
                ->where('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhere('full_name', 'like', "%{$term}%")
                ->orWhere('teacher_code', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%"));
        }
        if ($request->filled('department')) {
            $query->where('department_id', $request->input('department'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.teachers.index', [
            'teachers' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'stats' => [
                'total' => Teacher::count(),
                'active' => Teacher::where('status', 'active')->count(),
                'heads' => Teacher::where('designation', 'Head of Department')->count(),
                'departments' => Department::count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.teachers.create', $this->options());
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $teacher = Teacher::create($this->payload($request));
        $teacher->programs()->sync($this->programIds($request));

        return redirect()->route('teachers.show', $teacher)->with('status', 'Teacher created successfully.');
    }

    public function show(Teacher $teacher): View
    {
        $teacher->load([
            'department', 'campus', 'programs', 'metrics',
            'assignments' => fn ($q) => $q->with(['subject', 'course', 'program'])->latest('id')->limit(8),
            'activities' => fn ($q) => $q->latest('occurred_at')->limit(6),
        ]);

        return view('admin.teachers.show', ['teacher' => $teacher]);
    }

    public function edit(Teacher $teacher): View
    {
        return view('admin.teachers.edit', array_merge($this->options(), [
            'teacher' => $teacher->load('programs'),
        ]));
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $teacher->update($this->payload($request, $teacher));
        $teacher->programs()->sync($this->programIds($request));

        return redirect()->route('teachers.show', $teacher)->with('status', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $teacher->delete();

        return redirect()->route('teachers.index')->with('status', 'Teacher deleted successfully.');
    }

    private function options(): array
    {
        return [
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
        ];
    }

    /** @return array<string, mixed> */
    private function payload(FormRequest $request, ?Teacher $teacher = null): array
    {
        $data = Arr::except($request->validated(), ['programs']);

        $data['full_name'] = trim(($data['first_name'] ?? $teacher?->first_name ?? '')
            .' '.($data['last_name'] ?? $teacher?->last_name ?? '')) ?: ($data['full_name'] ?? null);
        $data['status'] = $request->input('status') ?: 'active';

        if ($request->hasFile('photo')) {
            if ($teacher?->photo_url) {
                Storage::disk('public')->delete($teacher->photo_url);
            }
            $data['photo_url'] = $request->file('photo')->store('teachers', 'public');
        }

        return $data;
    }

    /** @return array<int> */
    private function programIds(FormRequest $request): array
    {
        return array_map('intval', (array) ($request->validated()['programs'] ?? []));
    }
}
