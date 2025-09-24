<?php

namespace Modules\Appointment\database\seeders;

use Illuminate\Database\Seeder;

class ClinicalExaminationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('clinical_examinations')->delete();

        \DB::table('clinical_examinations')->insert(array(
            array(
                'id' => 1,
                'patient_history_id' => 1,
                'face_symmetry' => 'Normal',
                'tmj_status' => json_encode(['Normal']),
                'malocclusion_class' => null,
                'soft_tissue_status' => 'Abnormal',
                'teeth_status' => 'No missing teeth',
                'gingival_health' => 'Healthy',
                'bleeding_on_probing' => 0,
                'pocket_depths' => '2',
                'mobility' => 0,
                'bruxism' => 0,
                'created_at' => '2025-07-15 06:34:37',
                'updated_at' => '2025-07-15 06:34:37',
            ),
        ));
    }
}
