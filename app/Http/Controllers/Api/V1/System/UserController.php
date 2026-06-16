<?php

namespace App\Http\Controllers\Api\V1\System;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\System\StoreUserRequest;
use App\Http\Requests\System\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{
    protected array $filterable = ['status', 'gender', 'campus_id', 'department_id', 'employee_tier', 'preferred_language'];
    protected array $searchable = ['name', 'username', 'email', 'phone', 'employee_id'];
    protected array $sortable = ['id', 'name', 'email', 'status', 'created_at'];
    protected array $includable = ['campus', 'department', 'reportingManager', 'roles'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(User::query(), $request);

        return $this->respondSuccess(
            UserResource::collection($query->paginate($this->perPage($request))),
            'Users retrieved successfully.'
        );
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $roles = $data['roles'] ?? null;
        unset($data['roles']);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        if ($roles !== null) {
            $user->syncRoles($roles);
        }

        return $this->respondCreated(UserResource::make($user->load('roles')), 'User created successfully.');
    }

    public function show(User $user): JsonResponse
    {
        $user->load(['campus', 'department', 'reportingManager', 'roles']);

        return $this->respondSuccess(UserResource::make($user), 'User retrieved successfully.');
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();
        $roles = $data['roles'] ?? null;
        unset($data['roles']);

        if (array_key_exists('password', $data) && $data['password'] !== null) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        if ($roles !== null) {
            $user->syncRoles($roles);
        }

        return $this->respondSuccess(UserResource::make($user->load('roles')), 'User updated successfully.');
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return $this->respondNoContent('User deleted successfully.');
    }
}
