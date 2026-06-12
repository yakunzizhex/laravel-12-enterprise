<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct(
        private RoleService $roleService
    ) {}

    /**
     * Display roles list
     */
    public function index()
    {
        $roles = Role::with('permissions')->paginate(15);
        return view('admin.roles.index', ['roles' => $roles]);
    }

    /**
     * Show role create form
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store new role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'slug' => 'required|string|max:255|unique:roles,slug',
            'description' => 'nullable|string|max:500',
            'permissions' => 'nullable|array',
        ]);

        $role = $this->roleService->create($request->only('name', 'slug', 'description'));

        if ($request->has('permissions')) {
            $this->roleService->syncPermissions($role, $request->permissions);
        }

        return redirect('/admin/roles')->with('success', 'Role created');
    }

    /**
     * Show edit form
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', ['role' => $role->load('permissions')]);
    }

    /**
     * Update role
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->roleService->update($role, $request->only('name', 'slug', 'description', 'is_active'));

        if ($request->has('permissions')) {
            $this->roleService->syncPermissions($role, $request->permissions);
        }

        return redirect('/admin/roles')->with('success', 'Role updated');
    }

    /**
     * Delete role
     */
    public function destroy(Role $role)
    {
        $this->roleService->delete($role);
        return redirect('/admin/roles')->with('success', 'Role deleted');
    }
}
