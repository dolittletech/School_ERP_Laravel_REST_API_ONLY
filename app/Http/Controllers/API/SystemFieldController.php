<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SchSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemFieldController extends Controller
{
    public function index()
    {
        $setting = SchSetting::first();
        return response()->json(['data' => $setting ? $setting->toArray() : null], 200);
    }

    public function changeStatus(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer',
            'status' => 'required|in:yes,no',
            'role' => 'required|string'
        ]);

        return (new SchSetting)->changeStatus($data);
    }
}