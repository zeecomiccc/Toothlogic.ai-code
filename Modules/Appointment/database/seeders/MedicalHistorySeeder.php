<?php

namespace Modules\Appointment\database\seeders;

use Illuminate\Database\Seeder;

class MedicalHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('medical_histories')->delete();

        \DB::table('medical_histories')->insert(array(
            array(
                'id' => 1,
                'patient_history_id' => 1,
                'under_medical_treatment' => 0,
                'treatment_details' => 'N/A',
                'hospitalized_last_year' => 1,
                'hospitalization_reason' => 'Kidney Stones',
                'diseases' => json_encode(['High/Low Blood Pressure', 'Diabetes']),
                'pregnant_or_breastfeeding' => 'N/A',
                'taking_medications' => json_encode(['Herbal Supplements']),
                'known_allergies' => json_encode(['Other']),
                'created_at' => '2025-07-15 06:13:39',
                'updated_at' => '2025-07-15 06:13:39',
            ),
        ));
    }
}
