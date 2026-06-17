<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fee\StoreFeeCategoryRequest;
use App\Http\Requests\Fee\UpdateFeeCategoryRequest;
use App\Models\FeeCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class FeeCategoryController extends Controller implements HasMiddleware
{
    public const FEE_TYPES = ['one_time', 'monthly', 'annual', 'semester_based', 'quarterly'];

    public const STATUSES = ['active', 'inactive'];

    private const BOOLEANS = [
        'applies_to_school', 'applies_to_college', 'applies_to_university', 'late_fee_enabled',
        'tax_applicable', 'scholarship_eligible', 'refundable', 'auto_generate_on_admission',
    ];

    public static function middleware(): array
    {
        return [
            new Middleware('can:fee-categories.view', only: ['index']),
            new Middleware('can:fee-categories.create', only: ['create', 'store']),
            new Middleware('can:fee-categories.edit', only: ['edit', 'update']),
            new Middleware('can:fee-categories.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = FeeCategory::query();

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%"));
        }
        if ($request->filled('fee_type')) {
            $query->where('fee_type', $request->input('fee_type'));
        }

        return view('admin.fee-categories.index', [
            'categories' => $query->latest('id')->paginate(per_page())->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.fee-categories.create');
    }

    public function store(StoreFeeCategoryRequest $request): RedirectResponse
    {
        FeeCategory::create($this->payload($request));

        return redirect()->route('fee-categories.index')->with('status', 'Fee category created successfully.');
    }

    public function edit(FeeCategory $feeCategory): View
    {
        return view('admin.fee-categories.edit', ['category' => $feeCategory]);
    }

    public function update(UpdateFeeCategoryRequest $request, FeeCategory $feeCategory): RedirectResponse
    {
        $feeCategory->update($this->payload($request));

        return redirect()->route('fee-categories.index')->with('status', 'Fee category updated successfully.');
    }

    public function destroy(FeeCategory $feeCategory): RedirectResponse
    {
        $feeCategory->delete();

        return back()->with('status', 'Fee category deleted successfully.');
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
