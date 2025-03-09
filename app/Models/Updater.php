<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Updater extends Model
{
    protected $table = 'updates';
    protected $primaryKey = 'id';
    protected $fillable = ['version', 'last_checked', 'message', 'error'];
}