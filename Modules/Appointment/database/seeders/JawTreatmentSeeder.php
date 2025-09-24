<?php

namespace Modules\Appointment\database\seeders;

use Illuminate\Database\Seeder;

class JawTreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('jaw_treatments')->delete();

        \DB::table('jaw_treatments')->insert(array(
            array(
                'id' => 1,
                'patient_history_id' => 1,
                'upper_jaw_treatment' => 'Composite fillings in 14, 15; RCT completed on 11; Crown advised.',
                'lower_jaw_treatment' => 'RCT ongoing in 36; Crown advised post-treatment. Crown placed on 46.',
                'created_at' => '2025-07-15 06:47:15',
                'updated_at' => '2025-07-15 06:47:15',
            ),
        ));
    }
}
