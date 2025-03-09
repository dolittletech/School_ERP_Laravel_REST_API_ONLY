<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'languages';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'is_rtl'];
    protected $casts = ['is_rtl' => 'boolean'];
}
