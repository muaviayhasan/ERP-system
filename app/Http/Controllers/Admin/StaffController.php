<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\StoreStaffRequest;
use App\Http\Requests\Hr\UpdateStaffRequest;
use App\Models\Campus;
use App\Models\Department;
use App\Models\Staff;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StaffController extends Controller implements HasMiddleware
{
    public const STATUSES = ['active', 'inactive'];

    public const SHIFTS = ['Morning', 'Evening', 'Night', 'Rotational'];

    public const ROLES = [
        'Accountant', 'Receptionist', 'IT Manager', 'Administrator', 'Librarian Assistant',
        'Lab Assistant', 'Security', 'Janitor', 'Driver', 'Clerk',
    ];

    public static function middleware(): array
    {
        return [
            new Middleware('can:staff.view', only: ['index']),
            new Middleware('can:staff.create', only: ['create', 'store']),
            new Middleware('can:staff.edit', only: ['edit', 'update']),
            new Middleware('can:staff.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Staff::query()->with(['department', 'campus']);

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q
                ->where('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhere('full_name', 'like', "%{$term}%")
                ->orWhere('staff_code', 'like', "%{$term}%")
                ->orWhere('role', 'like', "%{$term}%"));
        }
        if ($request->filled('department')) {
            $query->where('department_id', $request->input('department'));
        }
        if ($request->filled('shift')) {
            $query->where('shift', $request->input('shift'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.staff.index', [
            'staff' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'stats' => [
                'total' => Staff::count(),
                'active' => Staff::where('status', 'active')->count(),
                'inactive' => Staff::where('status', 'inactive')->count(),
                'departments' => Department::count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.staff.create', $this->options());
    }

    public function store(StoreStaffRequest $request): RedirectResponse
    {
        Staff::create($this->payload($request));

        return redirect()->route('staff.index')->with('status', 'Staff member created successfully.');
    }

    public function edit(Staff $staff): View
    {
        return view('admin.staff.edit', array_merge($this->options(), ['staff' => $staff]));
    }

    public function update(UpdateStaffRequest $request, Staff $staff): RedirectResponse
    {
        $staff->update($this->payload($request, $staff));

        return redirect()->route('staff.index')->with('status', 'Staff member updated successfully.');
    }

    public function destroy(Staff $staff): RedirectResponse
    {
        $staff->delete();

        return back()->with('status', 'Staff member deleted successfully.');
    }

    private function options(): array
    {
        return [
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'departments' => Department::orderBy('name')->get(['id', 'name']),
            'managers' => Staff::orderBy('full_name')->get(['id', 'full_name', 'staff_code']),
        ];
    }

    /** @return array<string, mixed> */
    private function payload(FormRequest $request, ?Staff $staff = null): array
    {
        $data = $request->validated();

        $data['full_name'] = trim(($data['first_name'] ?? $staff?->first_name ?? '')
            .' '.($data['last_name'] ?? $staff?->last_name ?? '')) ?: ($data['full_name'] ?? null);
        $data['status'] = $request->input('status') ?: 'active';

        if ($request->hasFile('photo')) {
            if ($staff?->photo_url) {
                Storage::disk('public')->delete($staff->photo_url);
            }
            $data['photo_url'] = $request->file('photo')->store('staff', 'public');
        }

        return $data;
    }
}
