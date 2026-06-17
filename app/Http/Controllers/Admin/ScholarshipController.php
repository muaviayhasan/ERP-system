<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Scholarship\StoreScholarshipRequest;
use App\Http\Requests\Scholarship\UpdateScholarshipRequest;
use App\Models\Scholarship;
use App\Models\ScholarshipAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class ScholarshipController extends Controller implements HasMiddleware
{
    public const TYPES = ['merit', 'need', 'sports', 'institutional'];

    public const VALUE_TYPES = ['percentage', 'fixed_amount'];

    public const STATUSES = ['active', 'inactive'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:scholarships.view', only: ['index']),
            new Middleware('can:scholarships.create', only: ['create', 'store']),
            new Middleware('can:scholarships.edit', only: ['edit', 'update']),
            new Middleware('can:scholarships.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Scholarship::query()->withCount('assignments');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%"));
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.scholarships.index', [
            'scholarships' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'assignments' => ScholarshipAssignment::with(['student', 'scholarship'])->latest('id')->limit(10)->get(),
            'stats' => [
                'active' => Scholarship::where('status', 'active')->count(),
                'discount' => (float) ScholarshipAssignment::where('status', 'active')->sum('discount_amount'),
                'merit' => Scholarship::where('type', 'merit')->count(),
                'need' => Scholarship::where('type', 'need')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.scholarships.create');
    }

    public function store(StoreScholarshipRequest $request): RedirectResponse
    {
        Scholarship::create(array_merge($request->validated(), ['status' => $request->input('status') ?: 'active']));

        return redirect()->route('scholarships.index')->with('status', 'Scholarship created successfully.');
    }

    public function edit(Scholarship $scholarship): View
    {
        return view('admin.scholarships.edit', ['scholarship' => $scholarship]);
    }

    public function update(UpdateScholarshipRequest $request, Scholarship $scholarship): RedirectResponse
    {
        $scholarship->update(array_merge($request->validated(), ['status' => $request->input('status') ?: 'active']));

        return redirect()->route('scholarships.index')->with('status', 'Scholarship updated successfully.');
    }

    public function destroy(Scholarship $scholarship): RedirectResponse
    {
        $scholarship->delete();

        return back()->with('status', 'Scholarship deleted successfully.');
    }
}
