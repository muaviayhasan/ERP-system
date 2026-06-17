<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreSubjectRequest;
use App\Http\Requests\Academic\UpdateSubjectRequest;
use App\Models\Department;
use App\Models\SchoolClass;
use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class SubjectController extends Controller implements HasMiddleware
{
    public const CLASSIFICATIONS = ['Core', 'Elective', 'Practical', 'Optional'];

    public const TYPES = ['University', 'College', 'School', 'Vocational'];

    public const STATUSES = ['active', 'inactive'];

    private const BOOLEANS = ['prerequisites_required', 'lock_structural_changes'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:subjects.view', only: ['index']),
            new Middleware('can:subjects.create', only: ['create', 'store']),
            new Middleware('can:subjects.edit', only: ['edit', 'update']),
            new Middleware('can:subjects.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Subject::query()->with(['department', 'schoolClass']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%"));
        }
        if ($request->filled('classification')) {
            $query->where('classification', $request->input('classification'));
        }

        return view('admin.subjects.index', [
            'subjects' => $query->latest('id')->paginate(per_page())->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.subjects.create', $this->options());
    }

    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        Subject::create($this->payload($request));

        return redirect()->route('subjects.index')->with('status', 'Subject created successfully.');
    }

    public function edit(Subject $subject): View
    {
        return view('admin.subjects.edit', array_merge($this->options(), ['subject' => $subject]));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject): RedirectResponse
    {
        $subject->update($this->payload($request));

        return redirect()->route('subjects.index')->with('status', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return back()->with('status', 'Subject deleted successfully.');
    }

    private function options(): array
    {
        return [
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'classes' => SchoolClass::orderBy('name')->get(['id', 'name']),
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
