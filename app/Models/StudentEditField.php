<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentEditField extends Model
{
    protected $table = 'student_edit_fields';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'status'];
    protected $casts = ['status' => 'boolean'];

    public function get()
    {
        return $this->all(); // Placeholder
    }

    public function add(array $data)
    {
        return $this->create($data); // Placeholder
    }
}