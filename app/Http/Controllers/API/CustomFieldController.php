<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use App\Models\OnlineAdmissionCustomFieldValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomFieldController extends Controller
{
    public function get($id = null)
    {
        if ($id) {
            $customField = CustomField::find($id);
            return response()->json(['data' => $customField ? $customField->toArray() : null], 200);
        } else {
            $customFields = CustomField::orderBy('belong_to', 'asc')->orderBy('weight', 'asc')->get();
            return response()->json(['data' => $customFields], 200);
        }
    }

    public function remove($id)
    {
        try {
            DB::beginTransaction();
            $customField = CustomField::find($id);
            if ($customField) {
                $customField->delete();
                // Add logging if needed (e.g., Log::info("Deleted custom field with id $id"))
                DB::commit();
                return response()->json(['message' => 'Custom field deleted'], 200);
            }
            return response()->json(['message' => 'Custom field not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function add(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['belong_to' => 'required|string', 'weight' => 'integer', 'visible_on_table' => 'string|nullable']);
            if ($request->has('id')) {
                $customField = CustomField::find($request->input('id'));
                if ($customField) {
                    $customField->update($data);
                    $message = "Updated custom field with id " . $request->input('id');
                    $record_id = $request->input('id');
                }
            } else {
                $customField = CustomField::create($data);
                $message = "Inserted custom field with id " . $customField->id;
                $record_id = $customField->id;
            }
            // Add logging if needed (e.g., Log::info($message))
            DB::commit();
            return response()->json(['message' => $message, 'data' => $customField], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateOrder(Request $request)
    {
        try {
            $data = $request->validate(['data' => 'required|array']);
            $updateData = $data['data'];
            if (!empty($updateData)) {
                CustomField::upsert(
                    $updateData,
                    ['id'],
                    ['belong_to', 'weight', 'visible_on_table']
                );
                return response()->json(['message' => 'Order updated'], 200);
            }
            return response()->json(['message' => 'No data to update'], 400);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getByBelong($belong_to)
    {
        $customFields = CustomField::where('belong_to', $belong_to)
            ->orderBy('belong_to', 'asc')
            ->orderBy('weight', 'asc')
            ->get();
        return response()->json(['data' => $customFields], 200);
    }

    public function insertRecord(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['custom_value_array' => 'required|array', 'insert_id' => 'required|integer']);
            $customValueArray = $data['custom_value_array'];
            foreach ($customValueArray as &$value) {
                $value['belong_table_id'] = $data['insert_id'];
            }
            CustomFieldValue::insert($customValueArray);
            DB::commit();
            return response()->json(['message' => 'Records inserted'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateRecord(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['custom_value_array' => 'required|array', 'id' => 'required|integer', 'belong_to' => 'required|string']);
            $customValueArray = $data['custom_value_array'];
            foreach ($customValueArray as $value) {
                $existing = CustomFieldValue::where('belong_table_id', $data['id'])
                    ->where('custom_field_id', $value['custom_field_id'])
                    ->first();
                if ($existing) {
                    $existing->update($value);
                } else {
                    $value['belong_table_id'] = $data['id'];
                    CustomFieldValue::create($value);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Records updated'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getCustomFields($belongs_to, $display_table = null)
    {
        $query = CustomField::where('belong_to', $belongs_to);
        if ($display_table) {
            $query->where('visible_on_table', $display_table);
        }
        $customFields = $query->orderBy('weight', 'asc')->get();
        return response()->json(['data' => $customFields], 200);
    }

    public function onlineAdmissionInsertRecord(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['custom_value_array' => 'required|array', 'insert_id' => 'required|integer']);
            $customValueArray = $data['custom_value_array'];
            foreach ($customValueArray as &$value) {
                $value['belong_table_id'] = $data['insert_id'];
            }
            OnlineAdmissionCustomFieldValue::insert($customValueArray);
            DB::commit();
            return response()->json(['message' => 'Online admission records inserted'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function onlineAdmissionUpdateRecord(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['custom_value_array' => 'required|array', 'id' => 'required|integer', 'belong_to' => 'required|string']);
            $customValueArray = $data['custom_value_array'];
            foreach ($customValueArray as $value) {
                $existing = OnlineAdmissionCustomFieldValue::where('belong_table_id', $data['id'])
                    ->where('custom_field_id', $value['custom_field_id'])
                    ->first();
                if ($existing) {
                    $existing->update($value);
                } else {
                    $value['belong_table_id'] = $data['id'];
                    OnlineAdmissionCustomFieldValue::create($value);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Online admission records updated'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}