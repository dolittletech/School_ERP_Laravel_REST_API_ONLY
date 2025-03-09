<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReferenceController extends Controller
{
    public function index()
    {
        $references = Reference::orderBy('id')->get();
        return response()->json($references);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reference' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $reference = Reference::create($request->only(['reference', 'description']));
            DB::commit();
            return response()->json(['status' => 'success', 'data' => $reference, 'message' => 'Reference added'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $reference = Reference::findOrFail($id);
        return response()->json($reference);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reference' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $reference = Reference::findOrFail($id);
            $reference->update($request->only(['reference', 'description']));
            DB::commit();
            return response()->json(['status' => 'success', 'data' => $reference, 'message' => 'Reference updated']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $reference = Reference::findOrFail($id);
            $reference->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Reference deleted']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}