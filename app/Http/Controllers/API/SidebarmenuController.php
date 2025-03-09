<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SidebarMenu;
use App\Models\SidebarSubMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SidebarmenuController extends Controller
{
    public function get($id = null)
    {
        if ($id) {
            $menu = SidebarMenu::find($id);
            return response()->json(['data' => $menu ? $menu->toArray() : null], 200);
        } else {
            $menus = SidebarMenu::orderBy('id')->get();
            return response()->json(['data' => $menus], 200);
        }
    }

    public function remove($id)
    {
        try {
            DB::beginTransaction();
            $menu = SidebarMenu::find($id);
            if ($menu) {
                $menu->delete();
                // Add logging if needed (e.g., Log::info("Deleted menu with id $id"))
                DB::commit();
                return response()->json(['message' => 'Menu deleted'], 200);
            }
            return response()->json(['message' => 'Menu not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function add(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['sidebar_display' => 'boolean', 'level' => 'integer', 'menu' => 'required|string', 'permission_group_id' => 'exists:permission_group,id']);
            if ($request->has('id') && $request->input('id') > 0) {
                $menu = SidebarMenu::find($request->input('id'));
                if ($menu) {
                    $menu->update($data);
                    $message = "Updated menu with id " . $request->input('id');
                    $record_id = $request->input('id');
                }
            } else {
                $menu = SidebarMenu::create($data);
                $message = "Inserted menu with id " . $menu->id;
                $record_id = $menu->id;
            }
            // Add logging if needed (e.g., Log::info($message))
            DB::commit();
            return response()->json(['message' => $message, 'data' => $menu], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function addSubMenu(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['sidebar_menu_id' => 'required|exists:sidebar_menus,id', 'status' => 'boolean', 'level' => 'integer', 'key' => 'string|nullable', 'permission_group_id' => 'exists:permission_group,id']);
            if ($request->has('id') && $request->input('id') != 0) {
                $subMenu = SidebarSubMenu::find($request->input('id'));
                if ($subMenu) {
                    $subMenu->update($data);
                    $message = "Updated submenu with id " . $request->input('id');
                    $record_id = $request->input('id');
                }
            } else {
                $subMenu = SidebarSubMenu::create($data);
                $message = "Inserted submenu with id " . $subMenu->id;
                $record_id = $subMenu->id;
            }
            // Add logging if needed (e.g., Log::info($message))
            DB::commit();
            return response()->json(['message' => $message, 'data' => $subMenu], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getMenuwithSubmenus($sidebar_display = -1)
    {
        $query = SidebarMenu::select('sidebar_menus.*', 'permission_group.name as permission_group_name', 'permission_group.short_code as short_code')
            ->leftJoin('permission_group', 'permission_group.id', '=', 'sidebar_menus.permission_group_id');

        if ($sidebar_display > -1) {
            $query->where('sidebar_menus.sidebar_display', $sidebar_display);
        }

        if ($sidebar_display == 1) {
            $query->orderBy('level', 'asc');
        } else {
            $query->orderBy('menu', 'asc');
        }

        $menus = $query->get();
        foreach ($menus as $menu) {
            $menu->submenus = $this->getSubmenusByMenuId($menu->id, $sidebar_display);
        }
        return response()->json(['data' => $menus->isNotEmpty() ? $menus->toArray() : false], 200);
    }

    public function getSubmenusByMenuId($menu_id, $sidebar_display = 0)
    {
        $subMenus = SidebarSubMenu::select('sidebar_sub_menus.*', 'permission_group.name as permission_group_name', 'permission_group.short_code as short_code')
            ->leftJoin('permission_group', 'permission_group.id', '=', 'sidebar_sub_menus.permission_group_id')
            ->where('sidebar_menu_id', $menu_id)
            ->where('status', true) // Replaced is_active = 1 with status = true
            ->orderBy('level', 'asc')
            ->get();
        return $subMenus;
    }

    public function getSubmenuById($id)
    {
        $subMenu = SidebarSubMenu::find($id);
        return response()->json(['data' => $subMenu ? $subMenu->toArray() : null], 200);
    }

    public function updateMenuOrder(Request $request)
    {
        try {
            $data = $request->input('data', []);
            $idNotToBeReset = $request->input('id_not_to_be_reset', []);

            if (!empty($idNotToBeReset)) {
                SidebarMenu::whereNotIn('id', $idNotToBeReset)->update(['sidebar_display' => 0]);
            }

            if (!empty($data)) {
                SidebarMenu::upsert(
                    $data,
                    ['id'],
                    ['sidebar_display', 'level', 'menu', 'permission_group_id']
                );
            }
            return response()->json(['message' => 'Menu order updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateSubmenuOrder(Request $request)
    {
        try {
            $data = $request->validate(['data' => 'required|array']);
            if (!empty($data['data'])) {
                SidebarSubMenu::upsert(
                    $data['data'],
                    ['id'],
                    ['sidebar_menu_id', 'status', 'level', 'key', 'permission_group_id']
                );
                return response()->json(['message' => 'Submenu order updated'], 200);
            }
            return response()->json(['message' => 'No data to update'], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateSubmenuByKey(Request $request)
    {
        try {
            $data = $request->validate(['data' => 'required|array']);
            if (!empty($data['data'])) {
                SidebarSubMenu::upsert(
                    $data['data'],
                    ['key'],
                    ['sidebar_menu_id', 'status', 'level', 'permission_group_id']
                );
                return response()->json(['message' => 'Submenu updated by key'], 200);
            }
            return response()->json(['message' => 'No data to update'], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}