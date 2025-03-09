<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SidebarMenu extends Model
{
    protected $table = 'sidebar_menus';
    protected $primaryKey = 'id';
    protected $fillable = ['sidebar_display', 'level', 'menu', 'permission_group_id'];
    protected $casts = ['sidebar_display' => 'boolean'];

    public function permissionGroup()
    {
        return $this->belongsTo(PermissionGroup::class, 'permission_group_id');
    }

    public function subMenus()
    {
        return $this->hasMany(SidebarSubMenu::class, 'sidebar_menu_id');
    }
}