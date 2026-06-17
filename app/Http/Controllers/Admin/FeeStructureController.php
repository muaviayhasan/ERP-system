<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fee\StoreFeeStructureRequest;
use App\Http\Requests\Fee\UpdateFeeStructureRequest;
use App\Models\AcademicYear;
use App\Models\Campus;
use App\Models\FeeCategory;
use App\Models\FeeStructure;
use App\Models\Program;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FeeStructureController extends Controller implements HasMiddleware
{
    public const BILLING_CYCLES = ['Monthly', 'Quarterly', 'Semester', 'Annual', 'One-time'];

    public const STATUSES = ['draft', 'active', 'archived'];

    private const BOOLEANS = ['scholarship_available', 'installments_enabled'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:fee-structures.view', only: ['index']),
            new Middleware('can:fee-structures.create', only: ['create', 'store']),
            new Middleware('can:fee-structures.edit', only: ['edit', 'update']),
            new Middleware('can:fee-structures.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = FeeStructure::query()->with(['campus', 'program']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$term}%")->orWhere('code', 'like', "%{$term}%"));
        }
        if ($request->filled('campus')) {
            $query->where('campus_id', $request->input('campus'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.fee-structures.index', [
            'structures' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function create(): View
    {
        return view('admin.fee-structures.create', $this->options());
    }

    public function store(StoreFeeStructureRequest $request): RedirectResponse
    {
        $structure = DB::transaction(function () use ($request) {
            $structure = FeeStructure::create($this->payload($request));
            $this->syncComponents($structure, $request);

            return $structure;
        });

        return redirect()->route('fee-structures.index')->with('status', 'Fee structure created successfully.');
    }

    public function edit(FeeStructure $feeStructure): View
    {
        return view('admin.fee-structures.edit', array_merge($this->options(), [
            'structure' => $feeStructure->load('feeStructureComponents'),
        ]));
    }

    public function update(UpdateFeeStructureRequest $request, FeeStructure $feeStructure): RedirectResponse
    {
        DB::transaction(function () use ($request, $feeStructure) {
            $feeStructure->update($this->payload($request));
            $this->syncComponents($feeStructure, $request);
        });

        return redirect()->route('fee-structures.index')->with('status', 'Fee structure updated successfully.');
    }

    public function destroy(FeeStructure $feeStructure): RedirectResponse
    {
        $feeStructure->delete();

        return back()->with('status', 'Fee structure deleted successfully.');
    }

    private function options(): array
    {
        return [
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(['id', 'name']),
            'categories' => FeeCategory::orderBy('name')->get(['id', 'name']),
        ];
    }

    /** @return array<string, mixed> */
    private function payload(FormRequest $request): array
    {
        return array_merge(
            $request->validated(),
            ['status' => $request->input('status') ?: 'draft'],
            collect(self::BOOLEANS)->mapWithKeys(fn ($b) => [$b => $request->boolean($b)])->all(),
        );
    }

    /**
     * Replace the structure's line-item components and recompute total_fee as the
     * sum of component amounts so the header total always reconciles.
     */
    private function syncComponents(FeeStructure $structure, Request $request): void
    {
        $structure->feeStructureComponents()->delete();

        $total = 0.0;
        foreach ((array) $request->input('components', []) as $row) {
            $name = trim($row['name'] ?? '');
            if ($name === '') {
                continue;
            }
            $amount = round((float) ($row['amount'] ?? 0), 2);
            $total += $amount;

            $structure->feeStructureComponents()->create([
                'fee_category_id' => $row['fee_category_id'] ?? null,
                'name' => $name,
                'amount' => $amount,
                'frequency' => $row['frequency'] ?? 'one-time',
                'taxable' => ! empty($row['taxable']),
            ]);
        }

        if ($total > 0) {
            $structure->update(['total_fee' => $total]);
        }
    }
}
