<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ComplaintType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ComplaintTypeController extends Controller
{
    public function index()
    {
        $types = ComplaintType::orderBy('id')->get();
        return response()->json($types);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'complaint_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $type = ComplaintType::create($request->only(['complaint_type', 'description']));
            DB::commit();
            return response()->json(['status' => 'success', 'data' => $type, 'message' => 'Complaint type added'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $type = ComplaintType::findOrFail($id);
        return response()->json($type);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'complaint_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $type = ComplaintType::findOrFail($id);
            $type->update($request->only(['complaint_type', 'description']));
            DB::commit();
            return response()->json(['status' => 'success', 'data' => $type, 'message' => 'Complaint type updated']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $type = ComplaintType::findOrFail($id);
            $type->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Complaint type deleted']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
