<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StudentEditField;
use App\Models\OnlineStudent;
use Illuminate\Support\Facades\DB;

class SchSetting extends Model
{
    protected $table = 'sch_settings';
    protected $primaryKey = 'id';
    protected $fillable = [
        'lastname', 'middlename', 'lang_id', 'languages', 'class_teacher', 'cron_secret_key', 'timezone',
        'superadmin_restriction', 'student_timeline', 'name', 'email', 'biometric', 'biometric_device',
        'time_format', 'phone', 'attendence_type', 'address', 'dise_code', 'date_format', 'currency',
        'currency_place', 'start_month', 'start_week', 'session_id', 'fee_due_days', 'image', 'theme',
        'online_admission', 'is_duplicate_fees_invoice', 'is_student_house', 'is_blood_group', 'admin_logo',
        'admin_small_logo', 'mobile_api_url', 'app_primary_color_code', 'app_secondary_color_code', 'app_logo',
        'student_profile_edit', 'staff_barcode', 'student_barcode', 'student_panel_login', 'parent_panel_login',
        'currency_format', 'exam_result', 'base_url', 'folder_path', 'status', 'low_attendance_limit',
        'roll_no', 'category', 'religion', 'cast', 'mobile_no', 'student_email', 'admission_date', 'student_photo',
        'student_height', 'student_weight', 'measurement_date', 'father_name', 'father_phone', 'father_occupation',
        'father_pic', 'mother_name', 'mother_phone', 'mother_occupation', 'mother_pic', 'guardian_name',
        'guardian_phone', 'guardian_occupation', 'guardian_relation', 'guardian_email', 'guardian_pic',
        'guardian_address', 'current_address', 'permanent_address', 'route_list', 'hostel_id', 'bank_account_no',
        'bank_name', 'ifsc_code', 'national_identification_no', 'local_identification_no', 'rte',
        'previous_school_details', 'student_note', 'upload_documents', 'student_barcode', 'staff_designation',
        'staff_department', 'staff_last_name', 'staff_father_name', 'staff_mother_name', 'staff_date_of_joining',
        'staff_phone', 'staff_emergency_contact', 'staff_marital_status', 'staff_photo', 'staff_current_address',
        'staff_permanent_address', 'staff_qualification', 'staff_work_experience', 'staff_note', 'staff_epf_no',
        'staff_basic_salary', 'staff_contract_type', 'staff_work_shift', 'staff_work_location', 'staff_leaves',
        'staff_account_details', 'staff_social_media', 'staff_upload_documents', 'staff_barcode'
    ];
    protected $casts = [
        'superadmin_restriction' => 'boolean',
        'student_timeline' => 'boolean',
        'biometric' => 'boolean',
        'online_admission' => 'boolean',
        'is_duplicate_fees_invoice' => 'boolean',
        'is_student_house' => 'boolean',
        'is_blood_group' => 'boolean',
        'student_profile_edit' => 'boolean',
        'staff_barcode' => 'boolean',
        'student_barcode' => 'boolean',
        'student_panel_login' => 'boolean',
        'parent_panel_login' => 'boolean',
        'exam_result' => 'boolean',
        'languages' => 'json',
        'status' => 'boolean',
        'roll_no' => 'boolean',
        'lastname' => 'boolean',
        'middlename' => 'boolean',
        'category' => 'boolean',
        'religion' => 'boolean',
        'cast' => 'boolean',
        'mobile_no' => 'boolean',
        'student_email' => 'boolean',
        'admission_date' => 'boolean',
        'student_photo' => 'boolean',
        'student_height' => 'boolean',
        'student_weight' => 'boolean',
        'measurement_date' => 'boolean',
        'father_name' => 'boolean',
        'father_phone' => 'boolean',
        'father_occupation' => 'boolean',
        'father_pic' => 'boolean',
        'mother_name' => 'boolean',
        'mother_phone' => 'boolean',
        'mother_occupation' => 'boolean',
        'mother_pic' => 'boolean',
        'guardian_name' => 'boolean',
        'guardian_phone' => 'boolean',
        'guardian_occupation' => 'boolean',
        'guardian_relation' => 'boolean',
        'guardian_email' => 'boolean',
        'guardian_pic' => 'boolean',
        'guardian_address' => 'boolean',
        'current_address' => 'boolean',
        'permanent_address' => 'boolean',
        'route_list' => 'boolean',
        'hostel_id' => 'boolean',
        'bank_account_no' => 'boolean',
        'bank_name' => 'boolean',
        'ifsc_code' => 'boolean',
        'national_identification_no' => 'boolean',
        'local_identification_no' => 'boolean',
        'rte' => 'boolean',
        'previous_school_details' => 'boolean',
        'student_note' => 'boolean',
        'upload_documents' => 'boolean',
        'staff_designation' => 'boolean',
        'staff_department' => 'boolean',
        'staff_last_name' => 'boolean',
        'staff_father_name' => 'boolean',
        'staff_mother_name' => 'boolean',
        'staff_date_of_joining' => 'boolean',
        'staff_phone' => 'boolean',
        'staff_emergency_contact' => 'boolean',
        'staff_marital_status' => 'boolean',
        'staff_photo' => 'boolean',
        'staff_current_address' => 'boolean',
        'staff_permanent_address' => 'boolean',
        'staff_qualification' => 'boolean',
        'staff_work_experience' => 'boolean',
        'staff_note' => 'boolean',
        'staff_epf_no' => 'boolean',
        'staff_basic_salary' => 'boolean',
        'staff_contract_type' => 'boolean',
        'staff_work_shift' => 'boolean',
        'staff_work_location' => 'boolean',
        'staff_leaves' => 'boolean',
        'staff_account_details' => 'boolean',
        'staff_social_media' => 'boolean',
        'staff_upload_documents' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'lang_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency');
    }

    public function changeStatus($data)
    {
        try {
            DB::beginTransaction();

            $id = $data['id'] ?? $this->id ?? 1;
            $setting = $this->find($id);
            if (!$setting) {
                throw new \Exception('Setting not found');
            }

            $status = $data['status'] ?? 'no';
            $role = $data['role'] ?? '';

            $updateData = ['id' => $id];
            $fieldMap = [
                'is_student_house' => 'is_student_house',
                'roll_no' => 'roll_no',
                'lastname' => 'lastname',
                'middlename' => 'middlename',
                'category' => 'category',
                'religion' => 'religion',
                'cast' => 'cast',
                'mobile_no' => 'mobile_no',
                'student_email' => 'student_email',
                'admission_date' => 'admission_date',
                'student_photo' => 'student_photo',
                'is_blood_group' => 'is_blood_group',
                'student_height' => 'student_height',
                'student_weight' => 'student_weight',
                'measurement_date' => 'measurement_date',
                'father_name' => 'father_name',
                'father_phone' => 'father_phone',
                'father_occupation' => 'father_occupation',
                'father_pic' => 'father_pic',
                'mother_name' => 'mother_name',
                'mother_phone' => 'mother_phone',
                'mother_occupation' => 'mother_occupation',
                'mother_pic' => 'mother_pic',
                'guardian_name' => 'guardian_name',
                'guardian_phone' => 'guardian_phone',
                'guardian_occupation' => 'guardian_occupation',
                'guardian_relation' => 'guardian_relation',
                'guardian_email' => 'guardian_email',
                'guardian_pic' => 'guardian_pic',
                'guardian_address' => 'guardian_address',
                'current_address' => 'current_address',
                'permanent_address' => 'permanent_address',
                'route_list' => 'route_list',
                'hostel_id' => 'hostel_id',
                'bank_account_no' => 'bank_account_no',
                'bank_name' => 'bank_name',
                'ifsc_code' => 'ifsc_code',
                'national_identification_no' => 'national_identification_no',
                'local_identification_no' => 'local_identification_no',
                'rte' => 'rte',
                'previous_school_details' => 'previous_school_details',
                'student_note' => 'student_note',
                'upload_documents' => 'upload_documents',
                'student_barcode' => 'student_barcode',
                'staff_designation' => 'staff_designation',
                'staff_department' => 'staff_department',
                'staff_last_name' => 'staff_last_name',
                'staff_father_name' => 'staff_father_name',
                'staff_mother_name' => 'staff_mother_name',
                'staff_date_of_joining' => 'staff_date_of_joining',
                'staff_phone' => 'staff_phone',
                'staff_emergency_contact' => 'staff_emergency_contact',
                'staff_marital_status' => 'staff_marital_status',
                'staff_photo' => 'staff_photo',
                'staff_current_address' => 'staff_current_address',
                'staff_permanent_address' => 'staff_permanent_address',
                'staff_qualification' => 'staff_qualification',
                'staff_work_experience' => 'staff_work_experience',
                'staff_note' => 'staff_note',
                'staff_epf_no' => 'staff_epf_no',
                'staff_basic_salary' => 'staff_basic_salary',
                'staff_contract_type' => 'staff_contract_type',
                'staff_work_shift' => 'staff_work_shift',
                'staff_work_location' => 'staff_work_location',
                'staff_leaves' => 'staff_leaves',
                'staff_account_details' => 'staff_account_details',
                'staff_social_media' => 'staff_social_media',
                'staff_upload_documents' => 'staff_upload_documents',
                'staff_barcode' => 'staff_barcode'
            ];

            if (array_key_exists($role, $fieldMap)) {
                $updateData[$fieldMap[$role]] = ($status === 'yes') ? 1 : 0; // Use integer for TINYINT
                $this->update($updateData);
            } else {
                throw new \Exception('Invalid role specified');
            }

            // Conditional updates for student_edit_field_model and onlinestudent_model
            if ($this->findSelected($this->studentEditField()->get(), $role)) {
                if ($status === 'no') {
                    $insert = ['name' => $role, 'status' => 0];
                    $this->studentEditField()->add($insert);
                    if ($role === 'guardian_name') {
                        $insert = ['name' => 'if_guardian_is', 'status' => 0];
                        $this->studentEditField()->add($insert);
                    }
                }
            }

            if ($this->findSelected($this->onlineStudent()->getformfields(), $role)) {
                if ($status === 'no') {
                    $insert = ['name' => $role, 'status' => 0];
                    $this->onlineStudent()->addformfields($insert);
                    if ($role === 'guardian_name') {
                        $insert = ['name' => 'if_guardian_is', 'status' => 0];
                        $this->onlineStudent()->addformfields($insert);
                    }
                }
            }

            DB::commit();
            return response()->json(['message' => 'Status changed successfully', 'data' => $this], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    protected function findSelected($inserted_fields, $find)
    {
        foreach ($inserted_fields as $inserted_value) {
            if ($find === $inserted_value->name && $inserted_value->status) {
                return true;
            }
        }
        return false;
    }

    protected function studentEditField()
    {
        return app(StudentEditField::class);
    }

    protected function onlineStudent()
    {
        return app(OnlineStudent::class);
    }

    public function update(array $attributes = [], array $options = [])
    {
        return parent::update($attributes, $options);
    }

    public function find($id)
    {
        return static::find($id);
    }
}