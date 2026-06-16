<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{
    /** Roles that cannot be edited/deleted from the UI. */
    private const PROTECTED_ROLES = ['super-admin'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:roles.view', only: ['index']),
            new Middleware('can:roles.create', only: ['create', 'store']),
            new Middleware('can:roles.edit', only: ['edit', 'update']),
            new Middleware('can:roles.delete', only: ['destroy']),
        ];
    }

    public function index(): View
    {
        return view('admin.roles.index', [
            'roles' => Role::withCount(['users', 'permissions'])->orderBy('name')->get(),
            'protected' => self::PROTECTED_ROLES,
        ]);
    }

    public function create(): View
    {
        return view('admin.roles.create', [
            'modules' => $this->groupedPermissions(),
            'rolePermissions' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('roles.index')->with('status', "Role \"{$role->name}\" created successfully.");
    }

    public function edit(Role $role): View
    {
        abort_if(in_array($role->name, self::PROTECTED_ROLES, true), 403, 'This role is protected.');

        return view('admin.roles.edit', [
            'role' => $role,
            'modules' => $this->groupedPermissions(),
            'rolePermissions' => $role->permissions->pluck('name')->all(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        abort_if(in_array($role->name, self::PROTECTED_ROLES, true), 403, 'This role is protected.');

        $data = $this->validateData($request, $role->id);
        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('roles.index')->with('status', "Role \"{$role->name}\" updated successfully.");
    }

    public function destroy(Role $role): RedirectResponse
    {
        if (in_array($role->name, self::PROTECTED_ROLES, true)) {
            return back()->withErrors(['role' => 'This role is protected and cannot be deleted.']);
        }
        if ($role->users()->exists()) {
            return back()->withErrors(['role' => 'Cannot delete a role that is still assigned to users.']);
        }

        $name = $role->name;
        $role->delete();

        return back()->with('status', "Role \"{$name}\" deleted successfully.");
    }

    /**
     * Permissions grouped by their "{module}" prefix for the matrix UI.
     *
     * @return \Illuminate\Support\Collection<string, \Illuminate\Support\Collection<int, Permission>>
     */
    private function groupedPermissions()
    {
        return Permission::orderBy('name')->get()
            ->groupBy(fn (Permission $p) => Str::contains($p->name, '.') ? Str::beforeLast($p->name, '.') : 'general');
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9\- ]+$/i', Rule::unique('roles', 'name')->ignore($id)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);
    }
}
