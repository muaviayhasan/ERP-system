<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Academic\StoreBatchRequest;
use App\Http\Requests\Academic\UpdateBatchRequest;
use App\Models\Batch;
use App\Models\Campus;
use App\Models\Program;
use App\Models\SchoolClass;
use App\Models\Semester;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class BatchController extends Controller implements HasMiddleware
{
    public const TYPES = ['Regular', 'Morning', 'Evening', 'Weekend'];

    public const STATUSES = ['active', 'upcoming', 'completed'];

    public const WEEK_DAYS = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    private const BOOLEANS = ['allow_waitlist', 'installments_allowed', 'open_for_admissions'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:batches.view', only: ['index']),
            new Middleware('can:batches.create', only: ['create', 'store']),
            new Middleware('can:batches.edit', only: ['edit', 'update']),
            new Middleware('can:batches.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Batch::query()->with(['program', 'schoolClass']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%"));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('type')) {
            $query->where('batch_type', $request->input('type'));
        }

        return view('admin.batches.index', [
            'batches' => $query->latest('id')->paginate(per_page())->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.batches.create', $this->options());
    }

    public function store(StoreBatchRequest $request): RedirectResponse
    {
        Batch::create($this->payload($request));

        return redirect()->route('batches.index')->with('status', 'Batch created successfully.');
    }

    public function edit(Batch $batch): View
    {
        return view('admin.batches.edit', array_merge($this->options(), ['batch' => $batch]));
    }

    public function update(UpdateBatchRequest $request, Batch $batch): RedirectResponse
    {
        $batch->update($this->payload($request));

        return redirect()->route('batches.index')->with('status', 'Batch updated successfully.');
    }

    public function destroy(Batch $batch): RedirectResponse
    {
        $batch->delete();

        return back()->with('status', 'Batch deleted successfully.');
    }

    private function options(): array
    {
        return [
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
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
