<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    protected $table = 'notification_setting';
    protected $primaryKey = 'id';
    protected $fillable = ['type', 'status'];
    protected $casts = [
        'status' => 'boolean'
    ];
}