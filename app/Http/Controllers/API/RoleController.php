<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\PermissionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return response()->json(['data' => $roles], 200);
    }

    public function permission(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        if ($request->isMethod('post')) {
            $perCatPost = $request->input('per_cat', []);
            $roleId = $request->input('role_id', $id);
            $toBeInsert = [];
            $toBeUpdate = [];
            $toBeDelete = [];

            foreach ($perCatPost as $perCatPostValue) {
                $insertData = [];
                foreach (config('mailsms.perm_category', []) as $perKey => $perValue) {
                    $chkVal = $request->input($perValue . "-perm_" . $perCatPostValue);
                    if (isset($chkVal)) {
                        $insertData[$perValue] = 1;
                    } else {
                        $insertData[$perValue] = 0;
                    }
                }

                $prevId = $request->input('roles_permissions_id_' . $perCatPostValue, 0);
                if ($prevId != 0) {
                    if (array_sum($insertData) > 0) {
                        $insertData['id'] = $prevId;
                        $toBeUpdate[] = $insertData;
                    } else {
                        $toBeDelete[] = $prevId;
                    }
                } elseif (array_sum($insertData) > 0) {
                    $insertData['role_id'] = $roleId;
                    $insertData['perm_cat_id'] = $perCatPostValue;
                    $toBeInsert[] = $insertData;
                }
            }

            $this->getInsertBatch($roleId, $toBeInsert, $toBeUpdate, $toBeDelete);
            return response()->json(['message' => 'Permissions updated', 'data' => $role], 200);
        }

        $rolePermissions = $role->permissions;
        return response()->json(['data' => ['role' => $role, 'role_permission' => $rolePermissions]], 200);
    }

    protected function getInsertBatch($roleId, $toBeInsert, $toBeUpdate, $toBeDelete)
    {
        DB::beginTransaction();
        try {
            if (!empty($toBeInsert)) {
                foreach ($toBeInsert as &$insert) {
                    $insert['status'] = true; // Default status for new permissions
                }
                RolePermission::insert($toBeInsert);
            }
            if (!empty($toBeUpdate)) {
                foreach ($toBeUpdate as $update) {
                    RolePermission::where('id', $update['id'])->update($update);
                }
            }
            if (!empty($toBeDelete)) {
                RolePermission::whereIn('id', $toBeDelete)->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function edit(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ]);

        $role->update(['name' => $request->input('name')]);
        return response()->json(['message' => 'Role updated', 'data' => $role], 200);
    }

    public function delete($id)
    {
        $role = Role::find($id);
        if ($role) {
            $role->delete();
            return response()->json(['message' => 'Role deleted'], 200);
        }
        return response()->json(['message' => 'Role not found'], 404);
    }
}