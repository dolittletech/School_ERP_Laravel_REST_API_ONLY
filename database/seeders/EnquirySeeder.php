<?php

namespace Database\Seeders;
use App\Models\ClassModel;
use App\Models\Enquiry;
use App\Models\FollowUp;
use App\Models\Source;
use App\Models\Reference;
use Illuminate\Database\Seeder;

class EnquirySeeder extends Seeder
{
    public function run()
    {
        ClassModel::create(['class' => 'Class A']);
        Source::create(['name' => 'Website']);
        Reference::create(['name' => 'Friend']);

        $enquiry = Enquiry::create([
            'name' => 'John Doe',
            'contact' => '1234567890',
            'source' => 'Website',
            'date' => '2025-03-07',
            'class' => 1,
            'status' => 'active'
        ]);

        FollowUp::create([
            'enquiry_id' => $enquiry->id,
            'date' => '2025-03-08',
            'next_date' => '2025-03-10',
            'response' => 'Interested',
            'followup_by' => 'admin'
        ]);
    }
}