<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SidebarSubMenu extends Model
{
    protected $table = 'sidebar_sub_menus';
    protected $primaryKey = 'id';
    protected $fillable = ['sidebar_menu_id', 'status', 'level', 'key', 'permission_group_id'];
    protected $casts = ['status' => 'boolean'];

    public function sidebarMenu()
    {
        return $this->belongsTo(SidebarMenu::class, 'sidebar_menu_id');
    }

    public function permissionGroup()
    {
        return $this->belongsTo(PermissionGroup::class, 'permission_group_id');
    }
}