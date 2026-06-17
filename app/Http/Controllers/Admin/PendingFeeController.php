<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendingFee;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

/**
 * Pending Fee Management — outstanding dues are maintained by FeePaymentService
 * as payments are recorded, so this is a read-only monitoring screen.
 */
class PendingFeeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:pending-fees.view', only: ['index']),
        ];
    }

    public function index(Request $request): View
    {
        $query = PendingFee::query()->with(['student', 'program'])->where('amount_pending', '>', 0);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->whereHas('student', fn ($s) => $s->where('full_name', 'like', "%{$term}%")->orWhere('student_code', 'like', "%{$term}%"));
        }
        if ($request->filled('program')) {
            $query->where('program_id', $request->input('program'));
        }
        if ($request->filled('overdue')) {
            $query->whereDate('due_date', '<', today());
        }

        return view('admin.pending-fees.index', [
            'pendingFees' => $query->orderBy('due_date')->paginate(per_page())->withQueryString(),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
            'stats' => [
                'students' => PendingFee::where('amount_pending', '>', 0)->distinct('student_id')->count('student_id'),
                'amount' => (float) PendingFee::where('amount_pending', '>', 0)->sum('amount_pending'),
                'overdue' => PendingFee::where('amount_pending', '>', 0)->whereDate('due_date', '<', today())->count(),
            ],
        ]);
    }
}
