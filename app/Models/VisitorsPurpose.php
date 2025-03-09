<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorsPurpose extends Model
{
    protected $table = 'visitors_purpose';
    protected $fillable = ['visitors_purpose', 'description'];
}