<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Captcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CaptchaController extends Controller
{
    public function getSetting()
    {
        $captchas = Captcha::all();
        return response()->json(['data' => $captchas], 200);
    }

    public function getStatus($name)
    {
        $captcha = Captcha::where('name', $name)->first();
        return response()->json(['data' => $captcha ? $captcha->toArray() : null], 200);
    }

    public function updateStatus(Request $request)
    {
        try {
            $data = $request->validate(['name' => 'required|string|exists:captcha,name', 'status' => 'required|boolean']);
            $captcha = Captcha::where('name', $data['name'])->first();
            if ($captcha) {
                $captcha->update(['status' => $data['status']]);
                return response()->json(['message' => 'Status updated', 'data' => $captcha], 200);
            }
            return response()->json(['message' => 'Captcha not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}