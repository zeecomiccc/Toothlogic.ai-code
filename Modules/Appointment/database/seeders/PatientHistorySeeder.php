<?php

namespace Modules\Appointment\database\seeders;
use Modules\Appointment\Models\PatientHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('patient_histories')->delete();

        \DB::table('patient_histories')->insert(array(
            array(
                'id' => 1,
                'user_id' => 10,
                'encounter_id' => 3,
                'is_complete' => 1,
                'created_at' => '2025-07-15 05:41:47',
                'updated_at' => '2025-07-15 05:41:47',
            ),
        ));
    }
}
