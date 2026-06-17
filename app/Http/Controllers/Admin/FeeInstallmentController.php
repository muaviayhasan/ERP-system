<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fee\StoreFeeInstallmentRequest;
use App\Http\Requests\Fee\UpdateFeeInstallmentRequest;
use App\Models\FeeInstallment;
use App\Models\StudentFeeAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class FeeInstallmentController extends Controller implements HasMiddleware
{
    public const STATUSES = ['pending', 'paid', 'overdue', 'waived'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:fee-installments.view', only: ['index']),
            new Middleware('can:fee-installments.create', only: ['create', 'store']),
            new Middleware('can:fee-installments.edit', only: ['edit', 'update']),
            new Middleware('can:fee-installments.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = FeeInstallment::query()->with('studentFeeAssignment.student');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('assignment')) {
            $query->where('student_fee_assignment_id', $request->input('assignment'));
        }

        return view('admin.fee-installments.index', [
            'installments' => $query->orderBy('due_date')->paginate(per_page())->withQueryString(),
            'stats' => [
                'pending' => FeeInstallment::where('status', 'pending')->count(),
                'paid' => FeeInstallment::where('status', 'paid')->count(),
                'due_amount' => (float) FeeInstallment::where('status', '!=', 'paid')->sum('amount'),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.fee-installments.create', $this->options());
    }

    public function store(StoreFeeInstallmentRequest $request): RedirectResponse
    {
        FeeInstallment::create(array_merge($request->validated(), ['status' => $request->input('status') ?: 'pending']));

        return redirect()->route('fee-installments.index')->with('status', 'Installment created successfully.');
    }

    public function edit(FeeInstallment $feeInstallment): View
    {
        return view('admin.fee-installments.edit', array_merge($this->options(), ['installment' => $feeInstallment]));
    }

    public function update(UpdateFeeInstallmentRequest $request, FeeInstallment $feeInstallment): RedirectResponse
    {
        $feeInstallment->update(array_merge($request->validated(), ['status' => $request->input('status') ?: 'pending']));

        return redirect()->route('fee-installments.index')->with('status', 'Installment updated successfully.');
    }

    public function destroy(FeeInstallment $feeInstallment): RedirectResponse
    {
        $feeInstallment->delete();

        return back()->with('status', 'Installment deleted successfully.');
    }

    private function options(): array
    {
        return [
            'assignments' => StudentFeeAssignment::with('student')->latest('id')->get()
                ->mapWithKeys(fn ($a) => [$a->id => ($a->student?->full_name ?? 'Student #'.$a->student_id).' — '.format_money($a->final_payable)]),
        ];
    }
}
