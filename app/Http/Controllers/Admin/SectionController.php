<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreSectionRequest;
use App\Http\Requests\Academic\UpdateSectionRequest;
use App\Models\Campus;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class SectionController extends Controller implements HasMiddleware
{
    public const TYPES = ['Morning', 'Evening', 'Weekend', 'Batch'];

    public const STATUSES = ['active', 'inactive'];

    private const BOOLEANS = ['enable_waitlist', 'is_active', 'allow_admissions', 'lock_structure'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:sections.view', only: ['index']),
            new Middleware('can:sections.create', only: ['create', 'store']),
            new Middleware('can:sections.edit', only: ['edit', 'update']),
            new Middleware('can:sections.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Section::query()->with(['schoolClass', 'campus']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%"));
        }
        if ($request->filled('class')) {
            $query->where('class_id', $request->input('class'));
        }
        if ($request->filled('type')) {
            $query->where('section_type', $request->input('type'));
        }

        return view('admin.sections.index', [
            'sections' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'classes' => SchoolClass::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create(): View
    {
        return view('admin.sections.create', $this->options());
    }

    public function store(StoreSectionRequest $request): RedirectResponse
    {
        Section::create($this->payload($request));

        return redirect()->route('sections.index')->with('status', 'Section created successfully.');
    }

    public function edit(Section $section): View
    {
        return view('admin.sections.edit', array_merge($this->options(), ['section' => $section]));
    }

    public function update(UpdateSectionRequest $request, Section $section): RedirectResponse
    {
        $section->update($this->payload($request));

        return redirect()->route('sections.index')->with('status', 'Section updated successfully.');
    }

    public function destroy(Section $section): RedirectResponse
    {
        $section->delete();

        return back()->with('status', 'Section deleted successfully.');
    }

    private function options(): array
    {
        return [
            'classes' => SchoolClass::orderBy('name')->get(['id', 'name']),
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
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
