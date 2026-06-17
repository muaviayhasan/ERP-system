<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeReceipt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

/**
 * Fee receipts are issued automatically by FeePaymentService at collection time,
 * so this screen is read-only: browse and view/print a receipt.
 */
class FeeReceiptController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:fee-receipts.view', only: ['index', 'show']),
            new Middleware('can:fee-receipts.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = FeeReceipt::query()->with(['student', 'program']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q
                ->where('receipt_number', 'like', "%{$term}%")
                ->orWhere('transaction_id', 'like', "%{$term}%")
                ->orWhereHas('student', fn ($s) => $s->where('full_name', 'like', "%{$term}%")->orWhere('student_code', 'like', "%{$term}%")));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.fee-receipts.index', [
            'receipts' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'stats' => [
                'total' => FeeReceipt::count(),
                'collected' => (float) FeeReceipt::sum('amount_paid'),
            ],
        ]);
    }

    public function show(FeeReceipt $feeReceipt): View
    {
        return view('admin.fee-receipts.show', [
            'receipt' => $feeReceipt->load(['student.program', 'program', 'campus', 'collectedBy', 'feePayment']),
        ]);
    }

    public function destroy(FeeReceipt $feeReceipt): RedirectResponse
    {
        $feeReceipt->delete();

        return redirect()->route('fee-receipts.index')->with('status', 'Receipt deleted successfully.');
    }
}
