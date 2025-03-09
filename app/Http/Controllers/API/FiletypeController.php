<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Filetype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FiletypeController extends Controller
{
    public function get($id = null)
    {
        $filetype = Filetype::first(); // Matches the original behavior of getting the first row
        return response()->json(['data' => $filetype ? $filetype->toArray() : null], 200);
    }

    public function add(Request $request)
    {
        try {
            $data = $request->validate(['type' => 'string|nullable', 'status' => 'boolean']);
            $existing = Filetype::first(); // Check if any record exists

            if ($existing) {
                $existing->update($data);
                return response()->json(['message' => 'Filetype updated', 'data' => $existing], 200);
            } else {
                $filetype = Filetype::create($data);
                return response()->json(['message' => 'Filetype added', 'data' => $filetype], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}