<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $table = 'source'; // Matches your existing table name
    protected $fillable = ['source', 'description']; // Updated to match your schema
}
