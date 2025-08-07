<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolePermissionController extends Controller
{
    public function edit(int $id)
    {
        $role = Role::findOrFail($id);
        $modules = Module::whereNull('parent_id')
            ->where('status', '1')
            ->with(['children' => function ($q) {
                $q->where('status', '1');
            }])->get();
        $permissions = RolePermission::where('role_id', $id)->get()->keyBy('module_id');

        return view('ursbid-admin.roles.permissions', [
            'role' => $role,
            'modules' => $modules,
            'permissions' => $permissions,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
            'permissions.*.can_view' => 'nullable|in:1',
            'permissions.*.can_add' => 'nullable|in:1',
            'permissions.*.can_edit' => 'nullable|in:1',
            'permissions.*.can_delete' => 'nullable|in:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $permissions = $request->input('permissions', []);
        $moduleIds = Module::pluck('id')->toArray();

        foreach ($permissions as $moduleId => $perm) {
            if (!in_array($moduleId, $moduleIds)) {
                continue;
            }
            RolePermission::updateOrCreate(
                ['role_id' => $id, 'module_id' => $moduleId],
                [
                    'can_view' => isset($perm['can_view']) ? 1 : 0,
                    'can_add' => isset($perm['can_add']) ? 1 : 0,
                    'can_edit' => isset($perm['can_edit']) ? 1 : 0,
                    'can_delete' => isset($perm['can_delete']) ? 1 : 0,
                ]
            );
        }

        RolePermission::where('role_id', $id)
            ->whereNotIn('module_id', array_keys($permissions))
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Permissions updated successfully.',
        ]);
    }
}
