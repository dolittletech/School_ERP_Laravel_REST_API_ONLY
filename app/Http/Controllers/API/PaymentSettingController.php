<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentSettingController extends Controller
{
    public function get()
    {
        $paymentSettings = PaymentSetting::all();
        return response()->json(['data' => $paymentSettings], 200);
    }

    public function getActiveMethod()
    {
        $paymentSetting = PaymentSetting::where('status', true)->first(); // Replaced is_active = 'yes' with status = true
        return response()->json(['data' => $paymentSetting ? $paymentSetting->toArray() : null], 200);
    }

    public function add(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['payment_type' => 'required|string|unique:payment_settings,payment_type', 'status' => 'boolean']);
            $existing = PaymentSetting::where('payment_type', $data['payment_type'])->first();

            if ($existing) {
                $existing->update($data);
                $message = "Updated payment setting for type " . $data['payment_type'];
                $record_id = $existing->id;
            } else {
                $paymentSetting = PaymentSetting::create($data);
                $message = "Inserted payment setting with id " . $paymentSetting->id;
                $record_id = $paymentSetting->id;
            }

            // Add logging if needed (e.g., Log::info($message))
            DB::commit();
            return response()->json(['message' => $message, 'data' => $existing ?? $paymentSetting], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function validPaymentSetting(Request $request)
    {
        $paymentSetting = $request->input('payment_setting', 'none');
        if ($paymentSetting === 'none') {
            return response()->json(['valid' => true], 200);
        }
        $exists = $this->checkDataExists($paymentSetting);
        if (!$exists) {
            return response()->json(['valid' => false, 'message' => 'Please fill your payment setting detail'], 422);
        }
        return response()->json(['valid' => true], 200);
    }

    protected function checkDataExists($paymentSetting)
    {
        return PaymentSetting::where('payment_type', $paymentSetting)->exists();
    }

    public function active(Request $request, $other = false)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['payment_type' => 'required|string', 'status' => 'boolean']);
            if (!$other) {
                $paymentSetting = PaymentSetting::where('payment_type', $data['payment_type'])->first();
                if ($paymentSetting) {
                    $paymentSetting->update($data);
                    PaymentSetting::where('payment_type', '!=', $data['payment_type'])->update(['status' => false]); // Disable others
                }
            } else {
                PaymentSetting::where('id', $request->input('id'))->update($data); // Update all if other is true
            }
            DB::commit();
            return response()->json(['message' => 'Payment setting activated'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
