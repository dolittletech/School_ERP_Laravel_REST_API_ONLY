<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filetype extends Model
{
    protected $table = 'filetypes';
    protected $primaryKey = 'id';
    protected $fillable = ['type', 'status'];
    protected $casts = ['status' => 'boolean'];
}
