<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontCmsSetting extends Model
{
    protected $table = 'front_cms_settings';
    protected $primaryKey = 'id';
    protected $fillable = ['url']; // Add other fields as needed based on schema
}
