<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'complaints';
    protected $fillable = [
        'complaint_type', 'source', 'name', 'contact', 'date', 'description',
        'action_taken', 'assigned', 'note', 'image'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];
}
