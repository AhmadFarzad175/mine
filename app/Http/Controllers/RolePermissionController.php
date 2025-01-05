<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolePermissionRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\RolePermissionResource;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{

    
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');

        // Retrieve roles with pagination and search
        $roles = Role::query()
            ->where('name', 'like', '%' . $search . '%')
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'data' => RolePermissionResource::collection($roles),
            'meta' => [
                'current_page' => $roles->currentPage(),
                'from' => $roles->firstItem(),
                'to' => $roles->lastItem(),
                'total' => $roles->total(),
            ],
        ]);
    }


    public function indexRole(Request $request){
        $roles = Role::latest()->paginate($request->perPage);
        return $roles;
    }
    public function store(RolePermissionRequest $request)
    {
        // Use firstOrCreate to prevent duplicate roles
        $newRole = Role::firstOrCreate(
            ['name' => $request->role, 'guard_name' => 'web'],
            ['description' => $request->description]
        );

        // Create and assign permissions
        $permissionsArray = $request->input('permissions', []);
        $newRole->syncPermissions($permissionsArray);

        return new RolePermissionResource($newRole);
    }

    public function show($id)
    {
        // Fetch role with its permissions
        $role = Role::with('permissions:name')->findOrFail($id);

        return new RolePermissionResource($role);
    }

    public function update(RolePermissionRequest $request, $id)
    {
        $role = Role::findOrFail($id);

        // Update role details
        $role->update([
            'name' => $request->role,
            'description' => $request->description,
        ]);

        // Sync permissions
        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);

        return new RolePermissionResource($role);
    }

    public function destroy($id)
    {
        // Delete the role
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully'], 200);
    }
}
