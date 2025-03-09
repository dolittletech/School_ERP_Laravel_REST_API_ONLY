<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Certificate extends Model
{
    protected $table = 'certificates';
    protected $fillable = [
        'certificate_name', 'certificate_text', 'left_header', 'center_header', 'right_header',
        'left_footer', 'right_footer', 'center_footer', 'created_for', 'status', 'background_image',
        'header_height', 'content_height', 'footer_height', 'content_width', 'enable_student_image',
        'enable_image_height',
    ];

    public function addCertificate(array $data)
    {
        DB::beginTransaction();
        try {
            if (isset($data['id']) && $data['id']) {
                $certificate = $this->find($data['id']);
                if ($certificate) {
                    $certificate->update($data);
                    $this->log('Update', "Updated certificate id {$certificate->id}");
                    $record_id = $certificate->id;
                } else {
                    throw new \Exception('Certificate not found');
                }
            } else {
                $certificate = $this->create($data);
                $this->log('Insert', "Inserted certificate id {$certificate->id}");
                $record_id = $certificate->id;
            }
            DB::commit();
            return $record_id;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error adding certificate: " . $e->getMessage());
            return false;
        }
    }

    public function certificateList()
    {
        return $this->where('status', 1)
                    ->where('created_for', 2)
                    ->get();
    }

    public function getCertificate($id)
    {
        return $this->where('status', 1)
                    ->where('id', $id)
                    ->get();
    }

    public function remove($id)
    {
        DB::beginTransaction();
        try {
            $certificate = $this->find($id);
            if ($certificate) {
                $certificate->delete();
                $this->log('Delete', "Deleted certificate id {$id}");
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error deleting certificate: " . $e->getMessage());
            return false;
        }
    }

    public function getStudentCertificate()
    {
        return $this->where('created_for', 2)->get();
    }

    public function certificateById($id)
    {
        return $this->find($id);
    }

    private function log($action, $message)
    {
        \Log::info("{$action}: {$message}");
    }
}