<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'visitors_book';
    protected $fillable = [
        'purpose', 'name', 'contact', 'id_proof', 'no_of_pepple', 'date',
        'in_time', 'out_time', 'note', 'image'
    ];
}
