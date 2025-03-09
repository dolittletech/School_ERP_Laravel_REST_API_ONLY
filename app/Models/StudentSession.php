<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSession extends Model
{
    protected $table = 'student_session';
    protected $primaryKey = 'id';
    protected $fillable = ['student_id', 'session_id', 'class_id', 'section_id', 'default_login'];
    protected $casts = ['default_login' => 'boolean'];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}