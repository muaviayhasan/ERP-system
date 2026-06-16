<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:users.view', only: ['index']),
            new Middleware('can:users.create', only: ['create', 'store']),
            new Middleware('can:users.edit', only: ['edit', 'update']),
            new Middleware('can:users.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = User::query()->with('roles');

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('username', 'like', "%{$term}%"));
        }
        if ($request->filled('role')) {
            $query->whereHas('roles', fn ($q) => $q->where('name', $request->input('role')));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        return view('admin.users.index', [
            'users' => $query->latest('id')->paginate(15)->withQueryString(),
            'roles' => Role::orderBy('name')->pluck('name'),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', ['roles' => Role::orderBy('name')->pluck('name')]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'status' => $data['status'],
            'password' => Hash::make($data['password']),
        ]);
        $user->syncRoles($data['roles'] ?? []);

        return redirect()->route('users.index')->with('status', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user->load('roles'),
            'roles' => Role::orderBy('name')->pluck('name'),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validateData($request, $user->id);

        $user->update([
            'name' => $data['name'],
            'username' => $data['username'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'status' => $data['status'],
        ]);
        if (! empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }
        $user->syncRoles($data['roles'] ?? []);

        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return back()->with('status', 'User deleted successfully.');
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users', 'username')->ignore($id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended', 'pending'])],
            'password' => $id
                ? ['nullable', 'confirmed', Password::min(8)]
                : ['required', 'confirmed', Password::min(8)],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);
    }
}
