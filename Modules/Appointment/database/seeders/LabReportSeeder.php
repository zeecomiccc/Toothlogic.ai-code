<?php

namespace Modules\Appointment\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Lab\Models\Lab;
use App\Models\User;

class LabReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        \DB::table('labs')->delete();

        $labData = [
            'id' => 1,
            'patient_id' => 11,
            'doctor_id' => 19,
            'case_type' => 'Crown',
            'notes' => 'Patient requires full ceramic crown for molar.',
            'case_status' => 'created',
            'delivery_date_estimate' => '2025-08-10',
            'treatment_plan_link' => 'https://example.com/treatment-plan/1',
            'clear_aligner_impression_type' => 'digital',
            'prosthodontic_impression_type' => 'physical',
            'margin_location' => 'supragingival',
            'contact_tightness' => 'normal',
            'occlusal_scheme' => 'balanced',
            'temporary_placed' => true,
            'created_by' => 1,
            'updated_by' => 1,
            'deleted_by' => null,
            'created_at' => '2025-07-28 10:00:00',
            'updated_at' => '2025-07-28 10:00:00',
        ];

        $lab = Lab::create($labData);

        // Attach media files to different collections
        $this->attachMediaFiles($lab);
    }

    private function attachMediaFiles($lab)
    {
        if (!env('IS_DUMMY_DATA_IMAGE')) return false;

        // Sample files for different collections
        $mediaFiles = [
            'clear_aligner_intraoral' => [
                public_path('/img/logo/mini_logo.png'),
                public_path('/dummy-images/profile/user/robert.png'),
            ],
            'clear_aligner_pics' => [
                public_path('/dummy-images/profile/user/lisa.png'),
                public_path('/img/logo/mini_logo.png'),
            ],
            'clear_aligner_others' => [
                public_path('/img/logo/mini_logo.png'),
            ],
            'prostho_prep_scans' => [
                public_path('/dummy-images/profile/user/robert.png'),
                public_path('/img/logo/mini_logo.png'),
            ],
            'prostho_bite_scans' => [
                public_path('/img/logo/mini_logo.png'),
                public_path('/dummy-images/profile/user/lisa.png'),
            ],
            'prostho_preop_pictures' => [
                public_path('/dummy-images/profile/user/robert.png'),
                public_path('/img/logo/mini_logo.png'),
            ],
            'prostho_others' => [
                public_path('/img/logo/mini_logo.png'),
            ],
            'rx_prep_scan' => [
                public_path('/dummy-images/profile/user/lisa.png'),
                public_path('/img/logo/mini_logo.png'),
            ],
            'rx_bite_scan' => [
                public_path('/img/logo/mini_logo.png'),
                public_path('/dummy-images/profile/user/robert.png'),
            ],
            'rx_preop_images' => [
                public_path('/dummy-images/profile/user/lisa.png'),
                public_path('/img/logo/mini_logo.png'),
            ],
        ];

        foreach ($mediaFiles as $collection => $files) {
            foreach ($files as $filePath) {
                if (file_exists($filePath)) {
                    $file = new \Illuminate\Http\File($filePath);
                    $lab->addMedia($file)
                        ->preservingOriginal()
                        ->toMediaCollection($collection);
                }
            }
        }
    }
}
