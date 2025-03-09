<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispatchReceive extends Model
{
    protected $table = 'dispatch_receive';
    protected $fillable = [
        'reference_no', 'to_title', 'address', 'note', 'from_title',
        'date', 'type', 'image'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];
}
