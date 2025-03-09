<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Librarian extends Model
{
    protected $table = 'librarians';
    protected $primaryKey = 'id';
    protected $fillable = ['name'];
}