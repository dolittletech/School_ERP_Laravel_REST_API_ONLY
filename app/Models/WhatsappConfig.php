<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WhatsappConfig extends Model
{
    protected $table = 'whatsapp_configs';
    protected $fillable = ['account_sid', 'account_token', 'is_active'];

    public function getConfig($id = null)
    {
        if ($id) {
            return $this->where('id', $id)->first();
        }
        return $this->orderBy('id')->get();
    }

    public function add($data)
    {
        DB::beginTransaction();
        try {
            $config = $this->first();
            if ($config) {
                // Update existing record
                $config->update($data);
                $this->log('Update', "Updated whatsapp config id {$config->id}");
            } else {
                // Insert new record
                $config = $this->create($data);
                $this->log('Insert', "Inserted whatsapp config id {$config->id}");
            }
            DB::commit();
            return $config->id;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    // Placeholder for logging (implement as needed)
    private function log($action, $message)
    {
        // Example: Log to a logs table or Laravel's default log system
        \Log::info("{$action}: {$message}");
    }
}