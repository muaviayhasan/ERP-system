<?php

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\System\StoreRoleRequest;
use App\Http\Requests\System\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends ApiController
{
    protected array $filterable = ['name', 'guard_name'];
    protected array $searchable = ['name', 'guard_name'];
    protected array $sortable = ['id', 'name', 'created_at'];
    protected array $includable = ['permissions'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Role::query(), $request);

        return $this->respondSuccess(
            RoleResource::collection($query->paginate($this->perPage($request))),
            'Roles retrieved successfully.'
        );
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $data = $request->validated();
        $permissions = $data['permissions'] ?? null;
        unset($data['permissions']);

        $role = Role::create($data);

        if ($permissions !== null) {
            $role->syncPermissions($permissions);
        }

        return $this->respondCreated(RoleResource::make($role->load('permissions')), 'Role created successfully.');
    }

    public function show(Role $role): JsonResponse
    {
        $role->load(['permissions']);

        return $this->respondSuccess(RoleResource::make($role), 'Role retrieved successfully.');
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse
    {
        $data = $request->validated();
        $permissions = $data['permissions'] ?? null;
        unset($data['permissions']);

        $role->update($data);

        if ($permissions !== null) {
            $role->syncPermissions($permissions);
        }

        return $this->respondSuccess(RoleResource::make($role->load('permissions')), 'Role updated successfully.');
    }

    public function destroy(Role $role): JsonResponse
    {
        $role->delete();

        return $this->respondNoContent('Role deleted successfully.');
    }
}
