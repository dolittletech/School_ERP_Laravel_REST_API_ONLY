<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->hasMany(RolePermission::class, 'role_id');
    }
}
