<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreCampusRequest;
use App\Http\Requests\Academic\UpdateCampusRequest;
use App\Models\Campus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class CampusController extends Controller implements HasMiddleware
{
    /** Campus institution types and lifecycle statuses (mirrors the migration defaults). */
    public const TYPES = ['University', 'College', 'School', 'Vocational'];

    public const STATUSES = ['active', 'suspended', 'inactive'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:campuses.view', only: ['index']),
            new Middleware('can:campuses.create', only: ['create', 'store']),
            new Middleware('can:campuses.edit', only: ['edit', 'update']),
            new Middleware('can:campuses.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Campus::query()->withCount('departments');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%"));
        }
        if ($request->filled('type')) {
            $query->where('institution_type', $request->input('type'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.campuses.index', [
            'campuses' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'stats' => [
                'total' => Campus::count(),
                'active' => Campus::where('status', 'active')->count(),
                'universities' => Campus::where('institution_type', 'University')->count(),
                'admissions_open' => Campus::where('enable_online_admissions', true)->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.campuses.create');
    }

    public function store(StoreCampusRequest $request): RedirectResponse
    {
        Campus::create($this->payload($request));

        return redirect()->route('campuses.index')->with('status', 'Campus created successfully.');
    }

    public function edit(Campus $campus): View
    {
        return view('admin.campuses.edit', ['campus' => $campus]);
    }

    public function update(UpdateCampusRequest $request, Campus $campus): RedirectResponse
    {
        $campus->update($this->payload($request));

        return redirect()->route('campuses.index')->with('status', 'Campus updated successfully.');
    }

    public function destroy(Campus $campus): RedirectResponse
    {
        $campus->delete();

        return back()->with('status', 'Campus deleted successfully.');
    }

    /**
     * Validated data plus the three config toggles. Unchecked checkboxes are absent
     * from the request, so they are resolved to false rather than left untouched.
     *
     * @return array<string, mixed>
     */
    private function payload(FormRequest $request): array
    {
        return array_merge($request->validated(), [
            'enable_online_admissions' => $request->boolean('enable_online_admissions'),
            'centralized_fee_collection' => $request->boolean('centralized_fee_collection'),
            'hostel_management' => $request->boolean('hostel_management'),
        ]);
    }
}
