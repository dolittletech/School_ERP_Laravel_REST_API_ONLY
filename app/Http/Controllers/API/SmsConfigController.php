<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SmsConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SmsConfigController extends Controller
{
    public function get($id = null)
    {
        if ($id) {
            $smsConfig = SmsConfig::find($id);
            return response()->json(['data' => $smsConfig ? $smsConfig->toArray() : null], 200);
        } else {
            $smsConfigs = SmsConfig::orderBy('id')->get()->toArray();
            return response()->json(['data' => $smsConfigs], 200);
        }
    }

    public function changeStatus($type)
    {
        try {
            DB::beginTransaction();
            SmsConfig::where('type', '!=', $type)->update(['status' => false]); // Disabled all others
            DB::commit();
            return response()->json(['message' => 'Status updated for other types'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function add(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'type' => 'required|string|unique:sms_config,type',
                'status' => 'required|in:enabled,disabled'
            ]);
            $data['status'] = $data['status'] === 'enabled'; // Convert to boolean

            $existing = SmsConfig::where('type', $data['type'])->first();

            if ($existing) {
                $existing->update($data);
                $message = "Updated SMS config for type " . $data['type'];
                $record_id = $existing->id;
            } else {
                $smsConfig = SmsConfig::create($data);
                $message = "Inserted SMS config with id " . $smsConfig->id;
                $record_id = $smsConfig->id;
            }

            if ($data['status']) {
                $this->changeStatus($data['type']);
            }

            // Add logging if needed (e.g., Log::info($message))
            DB::commit();
            return response()->json(['message' => $message, 'data' => $existing ?? $smsConfig], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getActiveSMS()
    {
        $smsConfig = SmsConfig::where('status', true)->first();
        return response()->json(['data' => $smsConfig ? $smsConfig->toArray() : null], 200);
    }
}
