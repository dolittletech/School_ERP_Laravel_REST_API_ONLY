<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PermissionGroup;
use App\Models\PermissionStudent;
use App\Models\ModulePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    public function getPermission()
    {
        $permissions = PermissionGroup::where('system', false)->get()->toArray();
        return response()->json(['data' => $permissions], 200);
    }

    public function getParentPermission()
    {
        $permissions = PermissionStudent::where('system', false)->get()->toArray();
        return response()->json(['data' => $permissions], 200);
    }

    public function getStudentPermission()
    {
        $permissions = PermissionStudent::where('system', false)->get()->toArray();
        return response()->json(['data' => $permissions], 200);
    }

    public function changeStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['id' => 'required|exists:permission_group,id', 'status' => 'required|boolean']);
            $permissionGroup = PermissionGroup::find($data['id']);
            if ($permissionGroup) {
                $permissionGroup->update(['status' => $data['status']]);
                PermissionStudent::where('group_id', $data['id'])->update(['student' => $data['status'], 'parent' => $data['status']]);
                // Add logging if needed (e.g., Log::info("Updated permission group id {$data['id']}"))
                DB::commit();
                return response()->json(['message' => 'Status updated'], 200);
            }
            return response()->json(['message' => 'Permission group not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getPermissionByModulename($module_name)
    {
        $permission = PermissionGroup::select('status')->where('short_code', $module_name)->first();
        return response()->json(['data' => $permission ? $permission->toArray() : null], 200);
    }

    public function get($id = null)
    {
        if ($id) {
            $permission = PermissionGroup::find($id);
            return response()->json(['data' => $permission ? $permission->toArray() : null], 200);
        } else {
            $permissions = PermissionGroup::orderBy('id')->get();
            return response()->json(['data' => $permissions], 200);
        }
    }

    public function getParent($id = null)
    {
        if ($id) {
            $permission = PermissionStudent::find($id);
            return response()->json(['data' => $permission ? $permission->toArray() : null], 200);
        } else {
            $permissions = PermissionStudent::orderBy('id')->get();
            return response()->json(['data' => $permissions], 200);
        }
    }

    public function getStudent($id = null)
    {
        if ($id) {
            $permission = PermissionStudent::find($id);
            return response()->json(['data' => $permission ? $permission->toArray() : null], 200);
        } else {
            $permissions = PermissionStudent::orderBy('id')->get();
            return response()->json(['data' => $permissions], 200);
        }
    }

    public function getUserPermission($role)
    {
        $permissions = PermissionStudent::orderBy('id')->get();
        return response()->json(['data' => $permissions], 200);
    }

    public function changeParentStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['id' => 'required|exists:permission_student,id', 'parent' => 'required|boolean']);
            $permission = PermissionStudent::find($data['id']);
            if ($permission) {
                $permission->update(['parent' => $data['parent']]);
                // Add logging if needed (e.g., Log::info("Updated permission parent id {$data['id']}"))
                DB::commit();
                return response()->json(['message' => 'Parent status updated'], 200);
            }
            return response()->json(['message' => 'Permission not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function changeStudentStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['id' => 'required|exists:permission_student,id', 'student' => 'required|boolean']);
            $permission = PermissionStudent::find($data['id']);
            if ($permission) {
                $permission->update(['student' => $data['student']]);
                // Add logging if needed (e.g., Log::info("Updated permission student id {$data['id']}"))
                DB::commit();
                return response()->json(['message' => 'Student status updated'], 200);
            }
            return response()->json(['message' => 'Permission not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function hasModule($module_shortcode)
    {
        $count = PermissionGroup::where('short_code', $module_shortcode)->count();
        return response()->json(['data' => $count > 0], 200);
    }

    public function getModulePermissions($id = null)
    {
        if ($id) {
            $permission = ModulePermission::find($id);
            return response()->json(['data' => $permission ? $permission->toArray() : null], 200);
        } else {
            $permissions = ModulePermission::orderBy('id')->get();
            return response()->json(['data' => $permissions], 200);
        }
    }
}