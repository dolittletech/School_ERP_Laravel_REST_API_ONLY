<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationSettingController extends Controller
{
    public function get($id = null)
    {
        if ($id) {
            $notification = NotificationSetting::find($id);
            return response()->json(['data' => $notification ? $notification->toArray() : null], 200);
        } else {
            $notifications = NotificationSetting::orderBy('id')->get()->toArray();
            return response()->json(['data' => $notifications], 200);
        }
    }

    public function add(Request $request)
    {
        try {
            $data = $request->validate(['type' => 'required|string|unique:notification_setting,type']);
            $existing = NotificationSetting::where('type', $data['type'])->first();

            if ($existing) {
                $existing->update($data);
                $message = "Updated notification setting for type " . $data['type'];
                $record_id = $existing->id;
            } else {
                $notification = NotificationSetting::create($data);
                $message = "Inserted notification setting with id " . $notification->id;
                $record_id = $notification->id;
            }
            return response()->json(['message' => $message, 'data' => $notification ?? $existing], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['id' => 'required|exists:notification_setting,id', 'type' => 'string', 'status' => 'boolean']);
            $notification = NotificationSetting::find($data['id']);
            if ($notification) {
                $notification->update($data);
                $message = "Updated notification setting with id " . $data['id'];
                $record_id = $data['id'];
                DB::commit();
                return response()->json(['message' => $message, 'data' => $notification], 200);
            }
            return response()->json(['message' => 'Notification setting not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateBatch(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['data' => 'required|array']);
            $updateArray = $data['data'];

            if (!empty($updateArray)) {
                foreach ($updateArray as $item) {
                    $item = array_filter($item, function ($key) {
                        return in_array($key, ['id', 'type', 'status']);
                    }, ARRAY_FILTER_USE_KEY);
                    $request->validate($item, ['id' => 'required|exists:notification_setting,id', 'type' => 'string', 'status' => 'boolean']);
                }
                NotificationSetting::upsert(
                    $updateArray,
                    ['id'],
                    ['type', 'status']
                );
                foreach ($updateArray as $ua) {
                    $message = "Updated notification setting with id " . $ua['id'];
                    $record_id = $ua['id'];
                    // Add logging if needed (e.g., Log::info($message))
                }
                DB::commit();
                return response()->json(['message' => 'Batch update successful'], 200);
            }
            return response()->json(['message' => 'No data to update'], 400);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}