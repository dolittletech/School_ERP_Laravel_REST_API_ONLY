<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\VisitorsPurpose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VisitorsController extends Controller
{
    public function index()
    {
        $visitors = Visitor::orderBy('id', 'desc')->get();
        return response()->json($visitors);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purpose' => 'required|string',
            'name' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $visitorData = $request->only([
                'purpose', 'name', 'contact', 'id_proof', 'no_of_pepple', 'date',
                'in_time', 'out_time', 'note'
            ]);
            $visitor = Visitor::create($visitorData);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imgName = 'id' . $visitor->id . '.' . $file->getClientOriginalExtension();
                $file->storeAs('visitors', $imgName, 'public');
                $visitor->update(['image' => $imgName]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'data' => $visitor, 'message' => 'Visitor added'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $visitor = Visitor::findOrFail($id);
        return response()->json($visitor);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'purpose' => 'required|string',
            'name' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $visitor = Visitor::findOrFail($id);
            $visitorData = $request->only([
                'purpose', 'name', 'contact', 'id_proof', 'no_of_pepple', 'date',
                'in_time', 'out_time', 'note'
            ]);
            $visitor->update($visitorData);

            if ($request->hasFile('image')) {
                if ($visitor->image) {
                    Storage::disk('public')->delete('visitors/' . $visitor->image);
                }
                $file = $request->file('image');
                $imgName = 'id' . $id . '.' . $file->getClientOriginalExtension();
                $file->storeAs('visitors', $imgName, 'public');
                $visitor->update(['image' => $imgName]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'data' => $visitor, 'message' => 'Visitor updated']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $visitor = Visitor::findOrFail($id);
            if ($visitor->image) {
                Storage::disk('public')->delete('visitors/' . $visitor->image);
            }
            $visitor->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Visitor deleted']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getPurposes()
    {
        $purposes = VisitorsPurpose::all();
        return response()->json($purposes);
    }

    // Note: visitor_id_card_print and download methods require PDF generation and file handling,
    // which are better tested with Postman or a browser due to Tinker limitations.
}