<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintType extends Model
{
    protected $table = 'complaint_type'; // Matches your existing table name
    protected $fillable = ['complaint_type', 'description'];
}
