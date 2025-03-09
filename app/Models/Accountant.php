<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accountant extends Model
{
    protected $table = 'accountants';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];
}