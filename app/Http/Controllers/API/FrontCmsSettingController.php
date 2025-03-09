<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\FrontCmsSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontCmsSettingController extends Controller
{
    public function get($id = null)
    {
        if ($id) {
            $setting = FrontCmsSetting::find($id);
            return response()->json(['data' => $setting ? $setting->toArray() : null], 200);
        } else {
            $settings = FrontCmsSetting::orderBy('id')->get();
            return response()->json(['data' => $settings], 200);
        }
    }

    public function add(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['url' => 'string|nullable|unique:front_cms_settings,url']);
            if ($request->has('id') && $request->input('id') != 0) {
                $setting = FrontCmsSetting::find($request->input('id'));
                if ($setting) {
                    $setting->update($data);
                    $message = "Updated Front CMS Setting with id " . $request->input('id');
                    $record_id = $request->input('id');
                }
            } else {
                $setting = FrontCmsSetting::create($data);
                $message = "Inserted Front CMS Setting with id " . $setting->id;
                $record_id = $setting->id;
            }

            // Add logging if needed (e.g., Log::info($message))
            DB::commit();
            return response()->json(['message' => $message, 'data' => $setting], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function validCheckExists(Request $request)
    {
        $url = $request->input('url');
        $id = $request->input('id', 0);

        if ($this->checkDataExists($url, $id)) {
            return response()->json(['valid' => false, 'message' => 'URL already exists'], 422);
        }
        return response()->json(['valid' => true], 200);
    }

    protected function checkDataExists($url, $id)
    {
        $query = FrontCmsSetting::where('url', $url);
        if ($id > 0) {
            $query->where('id', '!=', $id); // Exclude the current record during update
        }
        return $query->exists();
    }
}
