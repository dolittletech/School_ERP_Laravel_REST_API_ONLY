<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintType;
use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::orderBy('id', 'desc')->get();
        return response()->json($complaints);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'complaint_type' => 'required|string',
            'source' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // 2MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $complaintData = $request->only([
                'complaint_type', 'source', 'name', 'contact', 'date', 'description',
                'action_taken', 'assigned', 'note'
            ]);
            $complaint = Complaint::create($complaintData);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imgName = 'id' . $complaint->id . '.' . $file->getClientOriginalExtension();
                $file->storeAs('complaints', $imgName, 'public');
                $complaint->update(['image' => $imgName]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'data' => $complaint, 'message' => 'Complaint added'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $complaint = Complaint::findOrFail($id);
        return response()->json($complaint);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'complaint_type' => 'required|string',
            'source' => 'required|string',
            'date' => 'required|date',
            'image' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $complaint = Complaint::findOrFail($id);
            $complaintData = $request->only([
                'complaint_type', 'source', 'name', 'contact', 'date', 'description',
                'action_taken', 'assigned', 'note'
            ]);
            $complaint->update($complaintData);

            if ($request->hasFile('image')) {
                if ($complaint->image) {
                    Storage::disk('public')->delete('complaints/' . $complaint->image);
                }
                $file = $request->file('image');
                $imgName = 'id' . $id . '.' . $file->getClientOriginalExtension();
                $file->storeAs('complaints', $imgName, 'public');
                $complaint->update(['image' => $imgName]);
            }

            DB::commit();
            return response()->json(['status' => 'success', 'data' => $complaint, 'message' => 'Complaint updated']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $complaint = Complaint::findOrFail($id);
            if ($complaint->image) {
                Storage::disk('public')->delete('complaints/' . $complaint->image);
            }
            $complaint->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Complaint deleted']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getComplaintTypes()
    {
        $types = ComplaintType::all();
        return response()->json($types);
    }

    public function getComplaintSources()
    {
        $sources = Source::all();
        return response()->json($sources);
    }

    public function download($id)
    {
        $complaint = Complaint::findOrFail($id);
        if ($complaint->image) {
            $filePath = storage_path('app/public/complaints/' . $complaint->image);
            if (file_exists($filePath)) {
                return response()->download($filePath, $complaint->image);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'File not found'], 404);
    }
}
