<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\StudentSession;
use App\Models\Teacher;
use App\Models\Accountant;
use App\Models\Librarian;
use App\Models\SchSetting;
use App\Models\Language;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    protected $currentSession;

    public function __construct()
    {
        $this->currentSession = SchSetting::first()->session_id ?? null; // Assuming session_id from SchSetting
    }

    public function add(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['username' => 'required|string|unique:users', 'password' => 'required|string', 'role' => 'string|nullable', 'status' => 'boolean', 'lang_id' => 'exists:languages,id', 'user_id' => 'exists:students,id', 'verification_code' => 'string|nullable', 'currency_id' => 'exists:currencies,id']);
            if ($request->has('id')) {
                $user = User::find($request->input('id'));
                if ($user) {
                    $user->update($data);
                    $message = "Updated user with id " . $request->input('id');
                    $record_id = $request->input('id');
                }
            } else {
                $user = User::create($data);
                $message = "Inserted user with id " . $user->id;
                $record_id = $user->id;
            }
            // Add logging if needed (e.g., Log::info($message))
            DB::commit();
            return response()->json(['message' => $message, 'data' => $user], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function addNewParent(Request $request)
    {
        try {
            DB::beginTransaction();
            $dataParentLogin = $request->validate(['username' => 'required|string|unique:users', 'password' => 'required|string', 'role' => 'required|in:parent']);
            $studentData = $request->validate(['parent_id' => 'exists:users,id', 'admission_no' => 'required|string|unique:students', 'firstname' => 'required|string']);

            $user = User::create($dataParentLogin);
            $studentData['parent_id'] = $user->id;
            // Assuming Student model exists with an add method or use create
            $student = new Student();
            $student->fill($studentData)->save();

            DB::commit();
            return response()->json(['message' => 'Parent and student added', 'data' => ['user' => $user, 'student' => $student]], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function checkLogin(Request $request)
    {
        $data = $request->validate(['username' => 'required|string', 'password' => 'required|string']);
        $setting = SchSetting::first();
        $studentLogin = json_decode($setting->student_login ?? '[]', true);
        $parentLogin = json_decode($setting->parent_login ?? '[]', true);

        $query = User::select('users.id as id', 'username', 'password', 'role', 'users.status as status', 'lang_id')
            ->join('students', 'students.id', '=', 'users.user_id')
            ->where('username', $data['username']);

        if (!empty($studentLogin) && in_array('admission_no', $studentLogin)) {
            $query->orWhere('students.admission_no', $data['username']);
        }
        if (!empty($studentLogin) && in_array('mobile_number', $studentLogin)) {
            $query->orWhere('students.mobileno', $data['username']);
        }
        if (!empty($studentLogin) && in_array('email', $studentLogin)) {
            $query->orWhere('students.email', $data['username']);
        }

        $result = $query->first();
        if ($result && $result->password === $data['password']) {
            return response()->json(['data' => $result], 200);
        }

        $query = User::select('users.id as id', 'username', 'password', 'role', 'users.status as status', 'lang_id')
            ->join('students', 'students.parent_id', '=', 'users.id')
            ->where('username', $data['username']);
        if (!empty($parentLogin) && in_array('mobile_number', $parentLogin)) {
            $query->orWhere('students.guardian_phone', $data['username']);
        }
        if (!empty($parentLogin) && in_array('email', $parentLogin)) {
            $query->orWhere('students.guardian_email', $data['username']);
        }

        $result1 = $query->first();
        if ($result1 && $result1->password === $data['password']) {
            return response()->json(['data' => $result1], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Implement other methods (checkLoginParent, read_user_information, etc.) similarly
    // For brevity, I'll provide key methods; you can expand as needed

    public function checkLoginParent(Request $request)
    {
        $data = $request->validate(['username' => 'required|string', 'password' => 'required|string']);
        $setting = SchSetting::first();
        $parentLogin = json_decode($setting->parent_login ?? '[]', true);

        $query = User::select('users.*', 'languages.language', 'students.admission_no', 'students.guardian_name', 'students.roll_no', 'students.admission_date', 'students.firstname', 'students.middlename', 'students.lastname', 'students.image', 'students.father_pic', 'students.mother_pic', 'students.guardian_pic', 'students.guardian_relation', 'students.mobileno', 'students.email', 'students.state', 'students.city', 'students.pincode', 'students.religion', 'students.dob', 'students.current_address', 'students.permanent_address', 'students.gender', 'students.guardian_phone', 'students.guardian_email', 'students.guardian_is', 'languages.is_rtl', 'currencies.short_name as currency_name', 'currencies.symbol as symbol', 'currencies.base_price as base_price', 'currencies.id as currency')
            ->join('students', 'students.parent_id', '=', 'users.id')
            ->join('languages', 'users.lang_id', '=', 'languages.id', 'left')
            ->join('currencies', 'currencies.id', '=', 'users.currency_id', 'left')
            ->where('username', $data['username']);

        if (!empty($parentLogin) && in_array('mobile_number', $parentLogin)) {
            $query->orWhere('students.guardian_phone', $data['username']);
        }
        if (!empty($parentLogin) && in_array('email', $parentLogin)) {
            $query->orWhere('students.guardian_email', $data['username']);
        }

        $result = $query->where('password', $data['password'])->first();
        return response()->json(['data' => $result ? $result->toArray() : null], 200);
    }

    public function readUserInformation($users_id)
    {
        $user = User::select('users.*', 'languages.language', 'students.firstname', 'students.middlename', 'students.image', 'students.lastname', 'students.guardian_name', 'students.gender', 'students.admission_no', 'students.email', 'currencies.short_name as currency_name', 'currencies.symbol as symbol', 'currencies.base_price as base_price', 'currencies.id as currency')
            ->join('students', 'students.id', '=', 'users.user_id')
            ->join('languages', 'languages.id', '=', 'users.lang_id', 'left')
            ->join('currencies', 'currencies.id', '=', 'users.currency_id', 'left')
            ->where('students.status', true) // Replaced is_active = 'yes' with status = true
            ->where('users.id', $users_id)
            ->first();
        return response()->json(['data' => $user ? $user->toArray() : null], 200);
    }

    // Add other read methods (read_teacher_information, etc.) similarly
}
