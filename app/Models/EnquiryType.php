<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnquiryType extends Model 
{
    protected $table = 'enquiry_type'; 
    protected $fillable = ['name'];
}
