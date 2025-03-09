<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    protected $table = 'custom_field_values';
    protected $primaryKey = 'id';
    protected $fillable = ['belong_table_id', 'custom_field_id', 'value'];

    public function customField()
    {
        return $this->belongsTo(CustomField::class, 'custom_field_id');
    }
}