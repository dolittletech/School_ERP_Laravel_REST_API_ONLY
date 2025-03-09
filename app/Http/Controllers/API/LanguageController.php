<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\SchSetting;
use App\Models\Staff;
use App\Models\User;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LanguageController extends Controller
{
    public function getRows(array $params = [])
    {
        $query = Language::orderBy('created_at', 'desc');
        if (isset($params['start']) && isset($params['limit'])) {
            $query->offset($params['start'])->limit($params['limit']);
        } elseif (isset($params['limit'])) {
            $query->limit($params['limit']);
        }
        $languages = $query->get();
        return response()->json(['data' => $languages->isNotEmpty() ? $languages->toArray() : false], 200);
    }

    public function get($id = null)
    {
        if ($id) {
            $language = Language::find($id);
            return response()->json(['data' => $language ? $language->toArray() : null], 200);
        } else {
            $languages = Language::orderBy('language', 'asc')->get();
            return response()->json(['data' => $languages->toArray()], 200);
        }
    }

    public function getEnableLanguages()
    {
        $schSetting = SchSetting::first();
        $languageIds = json_decode($schSetting->languages ?? '[]', true);
        $languages = Language::whereIn('id', $languageIds)->get()->toArray();
        return response()->json(['data' => $languages], 200);
    }

    public function remove($id)
    {
        try {
            DB::beginTransaction();
            $language = Language::find($id);
            if ($language) {
                $language->delete();
                // Add logging if needed (e.g., Log::info("Deleted language with id $id"))
                DB::commit();
                return response()->json(['message' => 'Language deleted'], 200);
            }
            return response()->json(['message' => 'Language not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function add(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validate(['language' => 'required|string']);
            if ($request->has('id')) {
                $language = Language::find($request->input('id'));
                if ($language) {
                    $language->update($data);
                }
            } else {
                $language = Language::create($data);
            }
            DB::commit();
            return response()->json(['message' => 'Language ' . ($request->has('id') ? 'updated' : 'added'), 'data' => $language], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function setUserLang($id, Request $request)
    {
        try {
            $data = $request->validate(['lang_id' => 'required|exists:languages,id']);
            $staff = Staff::find($id);
            if ($staff) {
                $staff->update(['lang_id' => $data['lang_id']]);
                return response()->json(['message' => 'Staff language updated', 'data' => $staff], 200);
            }
            return response()->json(['message' => 'Staff not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function setStudentLang($id, Request $request)
    {
        try {
            $data = $request->validate(['lang_id' => 'required|exists:languages,id']);
            $user = User::where('user_id', $id)->orWhere('id', $id)->first(); // Adjust based on your logic
            if ($user && $user->role === 'student') {
                $user->update(['lang_id' => $data['lang_id']]);
                return response()->json(['message' => 'Student language updated', 'data' => $user], 200);
            }
            $guest = Guest::find($id);
            if ($guest) {
                $guest->update(['lang_id' => $data['lang_id']]);
                return response()->json(['message' => 'Guest language updated', 'data' => $guest], 200);
            }
            return response()->json(['message' => 'User or Guest not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function setParentLang($id, Request $request)
    {
        try {
            $data = $request->validate(['lang_id' => 'required|exists:languages,id']);
            $user = User::find($id);
            if ($user) {
                $user->update(['lang_id' => $data['lang_id']]);
                return response()->json(['message' => 'Parent language updated', 'data' => $user], 200);
            }
            return response()->json(['message' => 'Parent not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function validCheckExists(Request $request)
    {
        $language = $request->input('language');
        $id = $request->input('id', 0);

        if ($this->checkDataExists($language, $id)) {
            return response()->json(['valid' => false, 'message' => 'Record already exists'], 422);
        }
        return response()->json(['valid' => true], 200);
    }

    protected function checkDataExists($language, $id)
    {
        return Language::where('language', $language)->where('id', '!=', $id)->exists();
    }

    public function update520()
    {
        $languages = Language::where('id', 93)->get()->toArray();
        return response()->json(['data' => $languages], 200);
    }
}
