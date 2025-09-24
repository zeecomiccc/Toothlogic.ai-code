<?php

namespace Modules\Appointment\database\seeders;

use Illuminate\Database\Seeder;

class RadiographicExaminationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('radiographic_examinations')->delete();

        \DB::table('radiographic_examinations')->insert(array(
            array(
                'id' => 1,
                'patient_history_id' => 1,
                'radiograph_type' => json_encode(['Bitewings']),
                'radiograph_findings' => 'No evidence of interproximal caries.',
                'created_at' => '2025-07-15 06:39:41',
                'updated_at' => '2025-07-15 06:39:41',
            ),
        ));
    }
}
