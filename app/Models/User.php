<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = ['username', 'password', 'role', 'status', 'lang_id', 'user_id', 'verification_code', 'currency_id'];
    protected $casts = ['status' => 'boolean'];

    public function language()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'id', 'user_id');
    }
}