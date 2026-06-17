<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreAcademicYearRequest;
use App\Http\Requests\Academic\UpdateAcademicYearRequest;
use App\Models\AcademicYear;
use App\Models\Campus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class AcademicYearController extends Controller implements HasMiddleware
{
    public const STATUSES = ['upcoming', 'active', 'completed'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:academic-years.view', only: ['index']),
            new Middleware('can:academic-years.create', only: ['create', 'store']),
            new Middleware('can:academic-years.edit', only: ['edit', 'update', 'activate']),
            new Middleware('can:academic-years.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = AcademicYear::query()->with('campuses');

        if ($request->filled('status') && in_array($request->input('status'), self::STATUSES, true)) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }

        return view('admin.academic-years.index', [
            'years' => $query->orderByDesc('start_date')->paginate(per_page())->withQueryString(),
            'counts' => [
                'active' => AcademicYear::where('status', 'active')->count(),
                'upcoming' => AcademicYear::where('status', 'upcoming')->count(),
                'completed' => AcademicYear::where('status', 'completed')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.academic-years.create', ['campuses' => Campus::orderBy('name')->get()]);
    }

    public function store(StoreAcademicYearRequest $request): RedirectResponse
    {
        if ($overlap = $this->overlapError($request)) {
            return $overlap;
        }

        $year = AcademicYear::create($this->payload($request));
        $this->syncCampuses($year, $request);

        return redirect()->route('academic-years.index')->with('status', 'Academic year created successfully.');
    }

    public function edit(AcademicYear $academicYear): View
    {
        return view('admin.academic-years.edit', [
            'academicYear' => $academicYear->load('campuses'),
            'campuses' => Campus::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear): RedirectResponse
    {
        if ($overlap = $this->overlapError($request, $academicYear->id)) {
            return $overlap;
        }

        $academicYear->update($this->payload($request));
        $this->syncCampuses($academicYear, $request);

        return redirect()->route('academic-years.index')->with('status', 'Academic year updated successfully.');
    }

    /** Promote a cycle to the active state (the "Activate" shortcut on the table). */
    public function activate(AcademicYear $academicYear): RedirectResponse
    {
        $academicYear->update(['status' => 'active']);

        return back()->with('status', "\"{$academicYear->name}\" is now the active academic year.");
    }

    public function destroy(AcademicYear $academicYear): RedirectResponse
    {
        $academicYear->delete();

        return back()->with('status', 'Academic year deleted successfully.');
    }

    /**
     * Validated data plus the boolean toggles (absent checkbox = false), with a
     * sensible default status when none is supplied.
     *
     * @return array<string, mixed>
     */
    private function payload(FormRequest $request): array
    {
        return array_merge(Arr::except($request->validated(), ['campuses']), [
            'status' => $request->input('status') ?: 'upcoming',
            'scope' => $request->input('scope', 'all_campuses'),
            'link_fee_structure' => $request->boolean('link_fee_structure'),
            'auto_roll_attendance' => $request->boolean('auto_roll_attendance'),
            'fees_configured' => $request->boolean('fees_configured'),
            'exams_configured' => $request->boolean('exams_configured'),
            'attendance_enabled' => $request->boolean('attendance_enabled'),
            'prevent_date_overlap' => $request->boolean('prevent_date_overlap'),
        ]);
    }

    /** Attach campuses only for a specific-campus scope; institution-wide cycles clear the pivot. */
    private function syncCampuses(AcademicYear $year, FormRequest $request): void
    {
        $ids = $request->input('scope') === 'specific_campuses'
            ? (array) ($request->validated()['campuses'] ?? [])
            : [];

        $year->campuses()->sync($ids);
    }

    /**
     * Honour the "Prevent Date Overlap" business rule: no two cycles may share a
     * date range over the same campus. Returns a redirect-back on violation, else null.
     */
    private function overlapError(FormRequest $request, ?int $ignoreId = null): ?RedirectResponse
    {
        if (! $request->boolean('prevent_date_overlap')) {
            return null;
        }

        $data = $request->validated();
        $scope = $request->input('scope', 'all_campuses');
        $targetCampuses = $scope === 'specific_campuses'
            ? array_map('intval', (array) ($data['campuses'] ?? []))
            : Campus::pluck('id')->all();

        $clash = AcademicYear::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('start_date', '<=', $data['end_date'])
            ->where('end_date', '>=', $data['start_date'])
            ->with('campuses')
            ->get()
            ->first(function (AcademicYear $other) use ($scope, $targetCampuses) {
                if ($scope !== 'specific_campuses' || $other->scope !== 'specific_campuses') {
                    return true; // an institution-wide cycle on either side always conflicts
                }
                $otherCampuses = $other->campuses->pluck('id')->all();

                return count(array_intersect($targetCampuses, $otherCampuses)) > 0;
            });

        if (! $clash) {
            return null;
        }

        return back()->withInput()->withErrors([
            'start_date' => "These dates overlap with \"{$clash->name}\". Disable “Prevent Date Overlap” or adjust the range.",
        ]);
    }
}
