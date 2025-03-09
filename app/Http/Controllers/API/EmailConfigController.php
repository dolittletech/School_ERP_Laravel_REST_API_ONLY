<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EmailConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailConfigController extends Controller
{
    public function get($id = null)
    {
        $emailConfig = EmailConfig::orderBy('id')->first(); // Matches the original behavior of getting the first row
        return response()->json(['data' => $emailConfig ? $emailConfig->toArray() : null], 200);
    }

    public function getEmailByType($email_type)
    {
        $emailConfig = EmailConfig::where('email_type', $email_type)->first();
        return response()->json(['data' => $emailConfig ? $emailConfig->toArray() : null], 200);
    }

    public function updateEmailConfig(Request $request, $email_type)
    {
        try {
            $data = $request->validate([
                'smtp_username' => 'required|string',
                'smtp_password' => 'required|string'
            ]);
            $emailConfig = EmailConfig::where('email_type', $email_type)->first();
            if ($emailConfig) {
                $emailConfig->update($data);
                return response()->json(['message' => 'Email config updated', 'data' => $emailConfig], 200);
            }
            return response()->json(['message' => 'Email config not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function add(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'email_type' => 'string|nullable',
                'smtp_username' => 'required|string',
                'smtp_password' => 'required|string',
                'status' => 'boolean'
            ]);

            $existing = EmailConfig::first(); // Check if any record exists

            if ($existing) {
                $existing->update($data);
                $message = "Updated email config with id " . $existing->id;
                $record_id = $existing->id;
            } else {
                $emailConfig = EmailConfig::create($data);
                $message = "Inserted email config with id " . $emailConfig->id;
                $record_id = $emailConfig->id;
            }

            // Add logging if needed (e.g., Log::info($message))
            DB::commit();
            return response()->json(['message' => $message, 'data' => $existing ?? $emailConfig], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getActiveEmail()
    {
        $emailConfig = EmailConfig::where('status', true)->first(); // Replaced is_active = 'yes' with status = true
        return response()->json(['data' => $emailConfig ? $emailConfig->toArray() : null], 200);
    }
}
