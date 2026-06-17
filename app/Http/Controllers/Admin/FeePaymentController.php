<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Fee\StoreFeePaymentRequest;
use App\Models\FeePayment;
use App\Models\StudentFeeAssignment;
use App\Services\Fees\FeePaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

/**
 * Fee Collection — records payments through FeePaymentService, which writes the
 * payment, receipt, ledger entry and balance updates atomically. The controller
 * never touches the ledger directly (financial source of truth stays in one place).
 */
class FeePaymentController extends Controller implements HasMiddleware
{
    public const METHODS = ['cash', 'bank', 'card', 'online'];

    public function __construct(private readonly FeePaymentService $payments)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:fee-payments.view', only: ['index']),
            new Middleware('can:fee-payments.create', only: ['create', 'store']),
        ];
    }

    public function index(Request $request): View
    {
        $query = FeePayment::query()->with(['student', 'studentFeeAssignment.program', 'receipt']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q
                ->where('transaction_id', 'like', "%{$term}%")
                ->orWhereHas('student', fn ($s) => $s->where('full_name', 'like', "%{$term}%")->orWhere('student_code', 'like', "%{$term}%")));
        }
        if ($request->filled('method')) {
            $query->where('payment_method', $request->input('method'));
        }

        return view('admin.fee-payments.index', [
            'payments' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'stats' => [
                'today' => (float) FeePayment::whereDate('paid_at', today())->sum('amount_paid'),
                'month' => (float) FeePayment::whereYear('paid_at', now()->year)->whereMonth('paid_at', now()->month)->sum('amount_paid'),
                'pending' => (float) StudentFeeAssignment::sum('total_pending'),
                'overdue' => StudentFeeAssignment::where('total_pending', '>', 0)
                    ->whereNotNull('next_due_date')->whereDate('next_due_date', '<', today())->count(),
            ],
        ]);
    }

    public function create(Request $request): View
    {
        $assignments = StudentFeeAssignment::with(['student', 'program'])
            ->where('total_pending', '>', 0)
            ->orderByDesc('id')->get();

        return view('admin.fee-payments.create', [
            'assignments' => $assignments,
            'selected' => $request->integer('assignment') ?: null,
            'methods' => self::METHODS,
        ]);
    }

    public function store(StoreFeePaymentRequest $request): RedirectResponse
    {
        $payment = $this->payments->record($request->validated(), $request->user()->id);

        return redirect()->route('fee-receipts.show', $payment->receipt_id)
            ->with('status', 'Payment of '.format_money($payment->amount_paid).' collected. Receipt issued.');
    }
}
