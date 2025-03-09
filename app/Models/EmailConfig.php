<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailConfig extends Model
{
    protected $table = 'email_config';
    protected $primaryKey = 'id';
    protected $fillable = ['email_type', 'smtp_username', 'smtp_password', 'status'];
    protected $casts = [
        'status' => 'boolean'
    ];
}
