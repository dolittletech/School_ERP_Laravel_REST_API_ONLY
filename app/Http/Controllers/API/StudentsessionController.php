<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\StudentSession;
use App\Models\SchSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentsessionController extends Controller
{
    protected $currentSession;

    public function __construct()
    {
        $this->currentSession = SchSetting::first()->session_id ?? null; // Assuming session_id from SchSetting
    }

    public function searchStudents($class_id = null, $section_id = null, $key = null)
    {
        $query = StudentSession::select('student_session.id', 'student_session.student_id', 'classes.class', 'sections.section',
            'students.firstname', 'students.middlename', 'students.lastname', 'students.admission_no', 'students.roll_no',
            'students.dob', 'students.guardian_name')
            ->join('classes', 'student_session.class_id', '=', 'classes.id')
            ->join('sections', 'sections.id', '=', 'student_session.section_id')
            ->join('students', 'students.id', '=', 'student_session.student_id')
            ->where('student_session.class_id', $class_id)
            ->where('student_session.section_id', $section_id)
            ->orderBy('student_session.id');

        $students = $query->get()->toArray();
        return response()->json(['data' => $students], 200);
    }

    public function searchStudentsBySession($student_session_id = null)
    {
        $student = StudentSession::select('students.admission_no', 'students.roll_no', 'student_session.session_id', 'student_session.class_id', 'student_session.section_id', 'student_session.id', 'student_session.student_id', 'classes.class', 'sections.section',
            'students.firstname', 'students.middlename', 'students.lastname', 'students.admission_no', 'students.mobileno', 'students.dob', 'students.guardian_name', 'students.father_name', 'students.guardian_phone', 'students.guardian_email', 'students.email', 'students.app_key', 'students.parent_app_key')
            ->join('classes', 'student_session.class_id', '=', 'classes.id')
            ->join('sections', 'sections.id', '=', 'student_session.section_id')
            ->join('students', 'students.id', '=', 'student_session.student_id')
            ->where('student_session.id', $student_session_id)
            ->orderBy('id')
            ->first();

        return response()->json(['data' => $student ? $student->toArray() : null], 200);
    }

    public function getStudentClass($id)
    {
        $student = StudentSession::select('students.admission_no', 'students.roll_no', 'student_session.session_id', 'student_session.class_id', 'student_session.section_id', 'student_session.id', 'student_session.student_id', 'classes.class', 'sections.section', 'students.firstname', 'students.middlename', 'students.lastname', 'students.admission_no', 'students.roll_no', 'students.dob', 'students.guardian_name')
            ->join('classes', 'student_session.class_id', '=', 'classes.id')
            ->join('sections', 'sections.id', '=', 'student_session.section_id')
            ->join('students', 'students.id', '=', 'student_session.student_id')
            ->where('student_id', $id)
            ->where('session_id', $this->currentSession)
            ->orderBy('id')
            ->first();

        return response()->json(['data' => $student ? $student->toArray() : null], 200);
    }

    public function updateById(Request $request)
    {
        try {
            $data = $request->validate(['id' => 'required|exists:student_session,id']);
            $studentSession = StudentSession::find($data['id']);
            if ($studentSession) {
                $studentSession->update($request->except('id'));
                return response()->json(['message' => 'Student session updated', 'data' => $studentSession], 200);
            }
            return response()->json(['message' => 'Student session not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function updatePromote(Request $request)
    {
        try {
            $data = $request->validate([
                'session_id' => 'required|exists:sessions,id',
                'student_id' => 'required|exists:students,id',
                'class_id' => 'required|exists:classes,id',
                'section_id' => 'required|exists:sections,id'
            ]);
            $studentSession = StudentSession::where('session_id', $data['session_id'])
                ->where('student_id', $data['student_id'])
                ->where('class_id', $data['class_id'])
                ->where('section_id', $data['section_id'])
                ->first();
            if ($studentSession) {
                $studentSession->update($request->except(['session_id', 'student_id', 'class_id', 'section_id']));
                return response()->json(['message' => 'Student session promoted', 'data' => $studentSession], 200);
            }
            return response()->json(['message' => 'Student session not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function getSessionById($id)
    {
        $session = StudentSession::find($id);
        return response()->json(['data' => $session ? $session->toArray() : null], 200);
    }

    public function getTotalStudentBySession()
    {
        $total = StudentSession::join('students', 'students.id', '=', 'student_session.student_id')
            ->where('student_session.session_id', $this->currentSession)
            ->where('students.status', true) // Replaced is_active = 'yes' with status = true
            ->count('student_session.id');
        return response()->json(['data' => ['total_student' => $total]], 200);
    }

    public function add(Request $request, $student_id)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate([
                'data' => 'required|array',
                'data.*.session_id' => 'required|exists:sessions,id',
                'data.*.student_id' => 'required|exists:students,id',
                'data.*.class_id' => 'required|exists:classes,id',
                'data.*.section_id' => 'required|exists:sections,id'
            ]);
            $insertArray = $data['data'];
            $notDelArray = [];

            foreach ($insertArray as $value) {
                $existing = StudentSession::where('session_id', $value['session_id'])
                    ->where('student_id', $value['student_id'])
                    ->where('class_id', $value['class_id'])
                    ->where('section_id', $value['section_id'])
                    ->first();
                if ($existing) {
                    $notDelArray[] = $existing->id;
                } else {
                    $session = new StudentSession();
                    $session->fill($value)->save();
                    $notDelArray[] = $session->id;
                }
            }

            if (!empty($notDelArray)) {
                StudentSession::where('session_id', $this->currentSession)
                    ->where('student_id', $student_id)
                    ->whereNotIn('id', $notDelArray)
                    ->delete();
            }

            DB::commit();
            return response()->json(['message' => 'Student sessions added', 'data' => $notDelArray], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function searchMultiStudentByClassSection($class_id = null, $section_id = null)
    {
        $students = app('App\Models\Student')->searchByClassSectionWithSession($class_id, $section_id); // Placeholder for student_model
        if (!empty($students)) {
            foreach ($students as $key => $student) {
                $sessions = StudentSession::where('student_id', $student['id'])
                    ->where('session_id', $this->currentSession)
                    ->orderBy('id')
                    ->get();
                $students[$key]['student_sessions'] = $sessions;
            }
        }
        return response()->json(['data' => $students], 200);
    }

    public function searchMultiClsSectionByStudent($student_id)
    {
        $sessions = StudentSession::select('student_session.*', 'classes.class', 'sections.section', 'student_session.id as student_session_id')
            ->join('classes', 'student_session.class_id', '=', 'classes.id')
            ->join('sections', 'sections.id', '=', 'student_session.section_id')
            ->where('student_id', $student_id)
            ->where('session_id', $this->currentSession)
            ->orderBy('student_session.default_login', 'desc')
            ->orderBy('id')
            ->get();
        return response()->json(['data' => $sessions], 200);
    }

    public function getMultiClsSectionByStudentOldSession($student_id)
    {
        $maxSessionId = StudentSession::where('student_id', $student_id)->max('session_id');
        $sessions = StudentSession::select('student_session.*', 'classes.class', 'sections.section', 'student_session.id as student_session_id')
            ->join('classes', 'student_session.class_id', '=', 'classes.id')
            ->join('sections', 'sections.id', '=', 'student_session.section_id')
            ->where('student_id', $student_id)
            ->where('session_id', $maxSessionId)
            ->orderBy('student_session.default_login', 'desc')
            ->orderBy('id')
            ->get();
        return response()->json(['data' => $sessions], 200);
    }

    public function searchActiveClassSectionStudent($student_id, $enable_session = null)
    {
        $query = StudentSession::select('student_session.*', 'classes.class', 'sections.section')
            ->join('classes', 'student_session.class_id', '=', 'classes.id')
            ->join('sections', 'sections.id', '=', 'student_session.section_id')
            ->where('student_id', $student_id);
        if ($enable_session === null) {
            $query->where('session_id', $this->currentSession);
        } else {
            $query->where('session_id', $enable_session);
        }
        $session = $query->first();
        return response()->json(['data' => $session ? $session->toArray() : null], 200);
    }
}