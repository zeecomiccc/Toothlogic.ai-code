<?php

namespace Modules\Appointment\database\seeders;

use Illuminate\Database\Seeder;

class InformedConsentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        \DB::table('informed_consents')->delete();

        \DB::table('informed_consents')->insert(array(
            array(
                'id' => 1,
                'patient_history_id' => 1,
                'patient_signature' => 'RM',
                'dentist_signature' => 'Dr. JP',
                'created_at' => '2025-07-15 06:39:41',
                'updated_at' => '2025-07-15 06:39:41',
            ),
        ));
    }
}
