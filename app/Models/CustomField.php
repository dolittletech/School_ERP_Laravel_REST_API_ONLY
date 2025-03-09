<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    protected $table = 'custom_fields';
    protected $primaryKey = 'id';
    protected $fillable = ['belong_to', 'weight', 'visible_on_table'];
}