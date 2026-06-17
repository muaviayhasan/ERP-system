<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fee\StoreFeePlanRequest;
use App\Http\Requests\Fee\UpdateFeePlanRequest;
use App\Models\FeePlan;
use App\Models\FeeStructure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class FeePlanController extends Controller implements HasMiddleware
{
    public const SCHEDULE_TYPES = ['installments', 'lump_sum', 'monthly', 'quarterly', 'full_payment'];

    public const STATUSES = ['active', 'inactive'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:fee-plans.view', only: ['index']),
            new Middleware('can:fee-plans.create', only: ['create', 'store']),
            new Middleware('can:fee-plans.edit', only: ['edit', 'update']),
            new Middleware('can:fee-plans.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = FeePlan::query()->with('feeStructure');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }
        if ($request->filled('schedule_type')) {
            $query->where('schedule_type', $request->input('schedule_type'));
        }

        return view('admin.fee-plans.index', [
            'plans' => $query->latest('id')->paginate(per_page())->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.fee-plans.create', $this->options());
    }

    public function store(StoreFeePlanRequest $request): RedirectResponse
    {
        FeePlan::create(array_merge($request->validated(), ['status' => $request->input('status') ?: 'active']));

        return redirect()->route('fee-plans.index')->with('status', 'Fee plan created successfully.');
    }

    public function edit(FeePlan $feePlan): View
    {
        return view('admin.fee-plans.edit', array_merge($this->options(), ['plan' => $feePlan]));
    }

    public function update(UpdateFeePlanRequest $request, FeePlan $feePlan): RedirectResponse
    {
        $feePlan->update(array_merge($request->validated(), ['status' => $request->input('status') ?: 'active']));

        return redirect()->route('fee-plans.index')->with('status', 'Fee plan updated successfully.');
    }

    public function destroy(FeePlan $feePlan): RedirectResponse
    {
        $feePlan->delete();

        return back()->with('status', 'Fee plan deleted successfully.');
    }

    private function options(): array
    {
        return ['structures' => FeeStructure::orderBy('name')->get(['id', 'name'])];
    }
}
