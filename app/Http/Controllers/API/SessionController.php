<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\SchSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    public function get($id = null)
    {
        if ($id) {
            $session = Session::find($id);
            return response()->json(['data' => $session ? $session->toArray() : null], 200);
        } else {
            $sessions = Session::orderBy('id')->get()->toArray();
            return response()->json(['data' => $sessions], 200);
        }
    }

    public function getAllSession()
    {
        $sessions = DB::select("
            SELECT sessions.*, IFNULL(sch_settings.session_id, 0) as active 
            FROM sessions 
            LEFT JOIN sch_settings ON sessions.id = sch_settings.session_id
        ");
        // Map 'active' to 'status' for consistency
        $result = array_map(function ($session) {
            $session->status = $session->active > 0;
            unset($session->active);
            return $session;
        }, $sessions);
        return response()->json(['data' => array_values($result)], 200);
    }

    public function getPreSession($session_id)
    {
        $session = DB::select("
            SELECT * FROM sessions 
            WHERE id = (SELECT MAX(id) FROM sessions WHERE id < ?)
        ", [$session_id])[0] ?? null;
        return response()->json(['data' => $session], 200);
    }

    public function getStudentAcademicSession($student_id = null)
    {
        if (!$student_id) {
            return response()->json(['message' => 'Student ID is required'], 400);
        }
        $sessions = Session::join('student_session', 'sessions.id', '=', 'student_session.session_id')
            ->where('student_session.student_id', $student_id)
            ->groupBy('student_session.session_id')
            ->orderBy('sessions.id')
            ->get(['sessions.*'])
            ->toArray();
        return response()->json(['data' => $sessions], 200);
    }

    public function add(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['session' => 'required|string', 'status' => 'boolean']);
            if (isset($data['id'])) {
                $session = Session::find($data['id']);
                if ($session) {
                    $session->update($data);
                    $message = "Updated session with id " . $data['id'];
                    $record_id = $data['id'];
                }
            } else {
                $session = Session::create($data);
                $message = "Inserted session with id " . $session->id;
                $record_id = $session->id;
            }
            DB::commit();
            return response()->json(['message' => $message, 'data' => $session], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function remove($id)
    {
        try {
            DB::beginTransaction();
            $session = Session::find($id);
            if ($session) {
                $session->delete();
                $message = "Deleted session with id " . $id;
                $record_id = $id;
                DB::commit();
                return response()->json(['message' => $message], 200);
            }
            return response()->json(['message' => 'Session not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}