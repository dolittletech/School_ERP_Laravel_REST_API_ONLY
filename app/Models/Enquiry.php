<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    protected $table = 'enquiry';
    
    protected $fillable = [
        'name', 'contact', 'address', 'reference', 'date', 'description',
        'follow_up_date', 'note', 'source', 'email', 'assigned', 'class',
        'no_of_child', 'status'
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class');
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class, 'enquiry_id');
    }
}
