<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';
    protected $fillable = ['admission_no', 'roll_no', 'firstname', 'middlename', 'lastname', 'dob', 'guardian_name', 'father_name', 'guardian_phone', 'guardian_email', 'email', 'mobileno', 'app_key', 'parent_app_key', 'status'];
    protected $casts = ['status' => 'boolean'];
}