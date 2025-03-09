<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Captcha extends Model
{
    protected $table = 'captcha';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'status'];
    protected $casts = ['status' => 'boolean'];
}