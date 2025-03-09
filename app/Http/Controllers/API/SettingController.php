<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SchSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function getMysqlVersion()
    {
        $mysqlVersion = DB::select('SELECT VERSION() as version')[0];
        return response()->json(['data' => $mysqlVersion], 200);
    }

    public function getSqlMode()
    {
        $sqlMode = DB::select('SELECT @@sql_mode as mode')[0];
        return response()->json(['data' => $sqlMode], 200);
    }

    public function get($id = null)
    {
        $query = SchSetting::select(
            'sch_settings.lastname', 'sch_settings.middlename', 'sch_settings.id', 'sch_settings.lang_id',
            'sch_settings.languages', 'sch_settings.class_teacher', 'sch_settings.cron_secret_key',
            'sch_settings.timezone', 'sch_settings.superadmin_restriction', 'sch_settings.student_timeline',
            'sch_settings.name', 'sch_settings.email', 'sch_settings.biometric', 'sch_settings.biometric_device',
            'sch_settings.time_format', 'sch_settings.phone', 'languages.language', 'sch_settings.attendence_type',
            'sch_settings.address', 'sch_settings.dise_code', 'sch_settings.date_format', 'sch_settings.currency',
            'sch_settings.currency_place', 'sch_settings.start_month', 'sch_settings.start_week',
            'sch_settings.session_id', 'sch_settings.fee_due_days', 'sch_settings.image', 'sch_settings.theme',
            'sessions.session', 'sch_settings.online_admission', 'sch_settings.is_duplicate_fees_invoice',
            'sch_settings.is_student_house', 'sch_settings.is_blood_group', 'sch_settings.admin_logo',
            'sch_settings.admin_small_logo', 'sch_settings.mobile_api_url', 'sch_settings.app_primary_color_code',
            'sch_settings.app_secondary_color_code', 'sch_settings.app_logo', 'sch_settings.student_profile_edit',
            'sch_settings.staff_barcode', 'sch_settings.student_barcode', 'languages.is_rtl',
            'sch_settings.student_panel_login', 'sch_settings.parent_panel_login', 'sch_settings.currency_format',
            'sch_settings.exam_result', 'sch_settings.base_url', 'sch_settings.folder_path', 'currencies.symbol as currency_symbol',
            'currencies.base_price', 'currencies.short_name as currency', 'currencies.id as currency_id',
            'sch_settings.admin_login_page_background', 'sch_settings.user_login_page_background',
            'sch_settings.low_attendance_limit'
        )
            ->leftJoin('sessions', 'sessions.id', '=', 'sch_settings.session_id')
            ->leftJoin('languages', 'languages.id', '=', 'sch_settings.lang_id')
            ->leftJoin('currencies', 'currencies.id', '=', 'sch_settings.currency');

        if ($id) {
            $query->where('sch_settings.id', $id);
            $result = $query->first();
            return response()->json(['data' => $result ? $result : null], 200);
        } else {
            $result = $query->orderBy('sch_settings.id')->get()->toArray();
            if (!empty($result)) {
                $result[0]['current_session'] = [
                    'session_id' => $result[0]['session_id'],
                    'session' => $result[0]['session']
                ];
            }
            return response()->json(['data' => $result], 200);
        }
    }

    public function add(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            if (isset($data['id'])) {
                $setting = SchSetting::find($data['id']);
                if ($setting) {
                    $setting->update($data);
                    $message = "Updated settings with id " . $data['id'];
                    $record_id = $data['id'];
                }
            } else {
                $setting = SchSetting::create($data);
                $message = "Inserted settings with id " . $setting->id;
                $record_id = $setting->id;
            }
            DB::commit();
            return response()->json(['message' => $message, 'data' => $setting], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function remove($id)
    {
        try {
            DB::beginTransaction();
            $setting = SchSetting::find($id);
            if ($setting) {
                $setting->delete();
                $message = "Deleted settings with id " . $id;
                $record_id = $id;
                DB::commit();
                return response()->json(['message' => $message], 200);
            }
            return response()->json(['message' => 'Setting not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // Add other methods (getSchoolDetail, getSetting, etc.) similarly
}