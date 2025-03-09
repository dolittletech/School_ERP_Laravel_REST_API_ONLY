<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsConfig extends Model
{
    protected $table = 'sms_config';
    protected $primaryKey = 'id';
    protected $fillable = ['type', 'status'];
    protected $casts = [
        'status' => 'boolean'
    ];
}