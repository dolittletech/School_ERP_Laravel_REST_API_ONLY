<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionGroup extends Model
{
    protected $table = 'permission_group';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'short_code', 'system', 'status'];
    protected $casts = ['system' => 'boolean', 'status' => 'boolean'];
}