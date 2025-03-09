<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineAdmissionCustomFieldValue extends Model
{
    protected $table = 'online_admission_custom_field_value';
    protected $primaryKey = 'id';
    protected $fillable = ['belong_table_id', 'custom_field_id', 'value'];

    public function customField()
    {
        return $this->belongsTo(CustomField::class, 'custom_field_id');
    }
}