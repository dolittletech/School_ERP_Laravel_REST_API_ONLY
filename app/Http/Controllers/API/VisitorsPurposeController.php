<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\VisitorsPurpose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VisitorsPurposeController extends Controller
{
    public function index()
    {
        $purposes = VisitorsPurpose::orderBy('id')->get();
        return response()->json($purposes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visitors_purpose' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $purpose = VisitorsPurpose::create($request->only(['visitors_purpose', 'description']));
            DB::commit();
            return response()->json(['status' => 'success', 'data' => $purpose, 'message' => 'Purpose added'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $purpose = VisitorsPurpose::findOrFail($id);
        return response()->json($purpose);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'visitors_purpose' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $purpose = VisitorsPurpose::findOrFail($id);
            $purpose->update($request->only(['visitors_purpose', 'description']));
            DB::commit();
            return response()->json(['status' => 'success', 'data' => $purpose, 'message' => 'Purpose updated']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $purpose = VisitorsPurpose::findOrFail($id);
            $purpose->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Purpose deleted']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
