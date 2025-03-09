<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $table = 'backup';
    protected $fillable = ['app_version', 'cron_secret_key'];

    public function get($id = null)
    {
        if ($id) {
            return $this->find($id);
        }
        return $this->all();
    }
}