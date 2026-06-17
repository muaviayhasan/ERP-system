<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreGuardianRequest;
use App\Http\Requests\Student\UpdateGuardianRequest;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class GuardianController extends Controller implements HasMiddleware
{
    public const RELATIONSHIPS = ['father', 'mother', 'guardian', 'sibling'];

    public const STATUSES = ['active', 'inactive'];

    private const BOOLEANS = ['is_primary_fee_payer', 'is_emergency_authorized', 'phone_verified'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:guardians.view', only: ['index']),
            new Middleware('can:guardians.create', only: ['create', 'store']),
            new Middleware('can:guardians.edit', only: ['edit', 'update']),
            new Middleware('can:guardians.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Guardian::query()->with('students')->withCount('students');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q
                ->where('full_name', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhere('cnic', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%"));
        }
        if ($request->filled('relationship')) {
            $query->where('relationship', $request->input('relationship'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.guardians.index', [
            'guardians' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'stats' => [
                'total' => Guardian::count(),
                'payers' => Guardian::where('is_primary_fee_payer', true)->count(),
                'emergency' => Guardian::where('is_emergency_authorized', true)->count(),
                'verified' => Guardian::where('phone_verified', true)->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.guardians.create', $this->options());
    }

    public function store(StoreGuardianRequest $request): RedirectResponse
    {
        $guardian = Guardian::create($this->payload($request));
        $this->syncStudents($guardian, $request);

        return redirect()->route('guardians.index')->with('status', 'Guardian created successfully.');
    }

    public function edit(Guardian $guardian): View
    {
        return view('admin.guardians.edit', array_merge($this->options(), [
            'guardian' => $guardian->load('students'),
        ]));
    }

    public function update(UpdateGuardianRequest $request, Guardian $guardian): RedirectResponse
    {
        $guardian->update($this->payload($request));
        $this->syncStudents($guardian, $request);

        return redirect()->route('guardians.index')->with('status', 'Guardian updated successfully.');
    }

    public function destroy(Guardian $guardian): RedirectResponse
    {
        $guardian->delete();

        return back()->with('status', 'Guardian deleted successfully.');
    }

    private function options(): array
    {
        return [
            'students' => Student::orderBy('full_name')->get(['id', 'full_name', 'student_code']),
        ];
    }

    /** @return array<string, mixed> */
    private function payload(FormRequest $request): array
    {
        return array_merge(
            Arr::except($request->validated(), ['students']),
            ['status' => $request->input('status') ?: 'active'],
            collect(self::BOOLEANS)->mapWithKeys(fn ($b) => [$b => $request->boolean($b)])->all(),
        );
    }

    /** Link students, stamping the guardian's relationship onto each pivot row. */
    private function syncStudents(Guardian $guardian, FormRequest $request): void
    {
        $ids = (array) ($request->validated()['students'] ?? []);
        $guardian->students()->sync(
            collect($ids)->mapWithKeys(fn ($id) => [(int) $id => ['relationship' => $guardian->relationship]])->all()
        );
    }
}
