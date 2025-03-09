<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $table = 'reference'; // Matches your existing table name
    protected $fillable = ['reference', 'description']; // Updated to match your schema
}