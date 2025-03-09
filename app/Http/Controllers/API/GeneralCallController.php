<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\GeneralCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GeneralCallController extends Controller
{
    public function index(Request $request)
    {
        $query = GeneralCall::query();

        // Optional filtering
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('call_type', 'like', "%{$search}%");
            });
        }

        $calls = $query->orderBy('id', 'desc')->get();
        return response()->json($calls);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'call_type' => 'required|string',
            'contact' => 'required|string',
            'date' => 'required|date',
            'name' => 'required|string',
            'follow_up_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $callData = $request->only([
                'name', 'contact', 'date', 'description', 'call_dureation',
                'note', 'call_type', 'follow_up_date'
            ]);
            $call = GeneralCall::create($callData);

            DB::commit();
            return response()->json(['status' => 'success', 'data' => $call, 'message' => 'Call added'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $call = GeneralCall::findOrFail($id);
        return response()->json($call);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'call_type' => 'required|string',
            'contact' => 'required|string',
            'date' => 'required|date',
            'name' => 'required|string',
            'follow_up_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'fail', 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $call = GeneralCall::findOrFail($id);
            $callData = $request->only([
                'name', 'contact', 'date', 'description', 'call_dureation',
                'note', 'call_type', 'follow_up_date'
            ]);
            $call->update($callData);

            DB::commit();
            return response()->json(['status' => 'success', 'data' => $call, 'message' => 'Call updated']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $call = GeneralCall::findOrFail($id);
            $call->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Call deleted']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // DataTables-like response (optional, for frontend compatibility)
    public function getCallList(Request $request)
    {
        $query = GeneralCall::select('id', 'name', 'contact', 'call_type', 'follow_up_date', 'date');

        if ($request->has('search') && $request->input('search.value')) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('call_type', 'like', "%{$search}%")
                  ->orWhere('date', 'like', "%{$search}%")
                  ->orWhere('follow_up_date', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $filtered = $total; // Simplified; adjust if filtering applied

        if ($request->has('order')) {
            $column = $request->input('order.0.column');
            $direction = $request->input('order.0.dir');
            $orderable = ['name', 'contact', 'date', 'follow_up_date', 'call_type'];
            if (isset($orderable[$column])) {
                $query->orderBy($orderable[$column], $direction);
            }
        }

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $calls = $query->offset($start)->limit($length)->get();

        return response()->json([
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $calls->map(function ($call) {
                return [
                    $call->name,
                    $call->contact,
                    $call->date->format('d/m/Y'),
                    $call->follow_up_date ? $call->follow_up_date->format('d/m/Y') : '',
                    $call->call_type,
                    '' // Placeholder for action buttons (client-side rendering)
                ];
            })->all(),
        ]);
    }
}
