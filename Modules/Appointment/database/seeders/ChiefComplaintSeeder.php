<?php

namespace Modules\Appointment\database\seeders;

use Illuminate\Database\Seeder;

class ChiefComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('chief_complaints')->delete();

        \DB::table('chief_complaints')->insert(array(
            array(
                'id' => 1,
                'patient_history_id' => 1,
                'complaint_notes' => 'Bad breath not improving with regular brushing',
                'created_at' => '2025-07-15 06:23:17',
                'updated_at' => '2025-07-15 06:23:17',
            ),
        ));
    }
}
