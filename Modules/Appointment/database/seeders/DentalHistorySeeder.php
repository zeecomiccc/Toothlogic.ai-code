<?php

namespace Modules\Appointment\database\seeders;

use Illuminate\Database\Seeder;

class DentalHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('dental_histories')->delete();

        \DB::table('dental_histories')->insert(array(
            array(
                'id' => 1,
                'patient_history_id' => 1,
                'last_dental_visit_date' => '2025-07-01',
                'reason_for_last_visit' => 'Routine Checkup',
                'issues_experienced' => json_encode(['Toothache or sensitivity','Difficulty chewing','Dry mouth']),
                'dental_anxiety_level' => 2,
                'created_at' => '2025-07-15 06:17:52',
                'updated_at' => '2025-07-15 06:17:52',
            ),
        ));
    }
}
