<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionStudent extends Model
{
    protected $table = 'permission_student';
    protected $primaryKey = 'id';
    protected $fillable = ['group_id', 'student', 'parent', 'status'];
    protected $casts = ['student' => 'boolean', 'parent' => 'boolean', 'status' => 'boolean'];

    public function permissionGroup()
    {
        return $this->belongsTo(PermissionGroup::class, 'group_id');
    }
}