<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\Enquiry;
use App\Models\FollowUp;
use App\Models\Source;
use App\Models\Reference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $source = $request->input('source');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');
        $status = $request->input('status', 'active');

        $query = Enquiry::with('class');

        if ($source) $query->where('source', $source);
        if ($status !== 'all') $query->where('status', $status);
        if ($date_from && $date_to) {
            $query->whereBetween('date', [$date_from, $date_to]);
        } elseif ($status === 'active') {
            $query->where('status', 'active');
        }

        $enquiries = $query->orderBy('id', 'desc')->get();
        foreach ($enquiries as $enquiry) {
            $followUp = $enquiry->followUps()->latest()->first();
            $enquiry->followupdate = $followUp->date ?? null;
            $enquiry->next_date = $followUp->next_date ?? null;
            $enquiry->response = $followUp->response ?? null;
            $enquiry->note = $followUp->note ?? null;
            $enquiry->followup_by = $followUp->followup_by ?? null;
        }

        return response()->json($enquiries);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact' => 'required|string',
            'source' => 'required|string',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        $enquiry = Enquiry::create($request->all() + ['status' => 'active']);
        return response()->json(['status' => 'success', 'data' => $enquiry, 'message' => 'Enquiry created'], 201);
    }

    public function show($id)
    {
        $enquiry = Enquiry::with('class')->findOrFail($id);
        return response()->json($enquiry);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact' => 'required|string',
            'source' => 'required|string',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        $enquiry = Enquiry::findOrFail($id);
        $enquiry->update($request->all());
        return response()->json(['status' => 'success', 'data' => $enquiry, 'message' => 'Enquiry updated']);
    }

    public function destroy($id)
    {
        $enquiry = Enquiry::findOrFail($id);
        $enquiry->delete();
        return response()->json(['status' => 'success', 'message' => 'Enquiry deleted']);
    }

    public function followUp(Request $request, $enquiryId)
    {
        $validator = Validator::make($request->all(), [
            'response' => 'required|string',
            'date' => 'required|date',
            'follow_up_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        $followUp = FollowUp::create([
            'enquiry_id' => $enquiryId,
            'date' => $request->date,
            'next_date' => $request->follow_up_date,
            'response' => $request->response,
            'note' => $request->note,
            'followup_by' => auth()->user()->username ?? 'system',
        ]);

        return response()->json(['status' => 'success', 'data' => $followUp, 'message' => 'Follow-up added'], 201);
    }

    public function getFollowUps($enquiryId)
    {
        $followUps = FollowUp::where('enquiry_id', $enquiryId)->orderBy('id', 'desc')->get();
        return response()->json($followUps);
    }

    public function deleteFollowUp($enquiryId, $followUpId)
    {
        $followUp = FollowUp::where('enquiry_id', $enquiryId)->findOrFail($followUpId);
        $followUp->delete();
        return response()->json(['status' => 'success', 'message' => 'Follow-up deleted']);
    }

    public function changeStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        $enquiry = Enquiry::findOrFail($id);
        $enquiry->update(['status' => $request->status]);
        return response()->json(['status' => 'success', 'message' => 'Status updated']);
    }

    public function getClasses()
    {
        return response()->json(ClassModel::all());
    }

    public function getSources()
    {
        return response()->json(Source::all());
    }

    public function getReferences()
    {
        return response()->json(Reference::all());
    }
}
