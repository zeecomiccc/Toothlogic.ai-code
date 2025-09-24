<?php

namespace Modules\Appointment\database\seeders;

use Illuminate\Database\Seeder;

class DiagnosisAndPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('diagnoses_and_plans')->delete();

        \DB::table('diagnoses_and_plans')->insert(array(
            array(
                'id' => 1,
                'patient_history_id' => 1,
                'diagnosis' => null,
                'proposed_treatments' => json_encode(['Fillings', 'Root Canal Treatment', 'Crowns/Bridges']),
                'planned_timeline' => '3 days',
                'alternatives_discussed' => 1,
                'risks_explained' => 1,
                'questions_addressed' => 1,
                'created_at' => '2025-07-15 06:41:58',
                'updated_at' => '2025-07-15 06:41:58',
            ),
        ));
    }
}
