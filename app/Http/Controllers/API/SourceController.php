<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SourceController extends Controller
{
    public function index()
    {
        $sources = Source::orderBy('id')->get();
        return response()->json($sources);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $source = Source::create($request->only(['source', 'description']));
            DB::commit();
            return response()->json(['status' => 'success', 'data' => $source, 'message' => 'Source added'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $source = Source::findOrFail($id);
        return response()->json($source);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'source' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $source = Source::findOrFail($id);
            $source->update($request->only(['source', 'description']));
            DB::commit();
            return response()->json(['status' => 'success', 'data' => $source, 'message' => 'Source updated']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $source = Source::findOrFail($id);
            $source->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Source deleted']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}