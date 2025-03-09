<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $table = 'payment_settings';
    protected $primaryKey = 'id';
    protected $fillable = ['payment_type', 'status'];
    protected $casts = [
        'status' => 'boolean'
    ];
}
