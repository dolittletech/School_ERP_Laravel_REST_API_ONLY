<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralCall extends Model
{
    protected $table = 'general_calls';
    protected $fillable = [
        'name', 'contact', 'date', 'description', 'call_dureation',
        'note', 'call_type', 'follow_up_date'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'follow_up_date' => 'date:Y-m-d',
    ];
}