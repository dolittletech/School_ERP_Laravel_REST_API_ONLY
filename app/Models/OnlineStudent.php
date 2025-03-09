<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineStudent extends Model
{
    protected $table = 'online_students';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'status'];
    protected $casts = ['status' => 'boolean'];

    public function getformfields()
    {
        return $this->all(); // Placeholder
    }

    public function addformfields(array $data)
    {
        return $this->create($data); // Placeholder
    }
}   