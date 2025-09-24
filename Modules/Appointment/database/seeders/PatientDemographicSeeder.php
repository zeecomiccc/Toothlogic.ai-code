<?php

namespace Modules\Appointment\database\seeders;

use Illuminate\Database\Seeder;

class PatientDemographicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('patient_demographics')->delete();

        \DB::table('patient_demographics')->insert(array(
            array(
                'id' => 1,
                'patient_history_id' => 1,
                'full_name' => 'Robert Martin',
                'dob' => '1993-10-05',
                'gender' => 'Male',
                'address' => '6 Heart Street, Cityville, USA',
                'phone' => '+91 7485961545',
                'email' => 'robert@gmail.com',
                'occupation' => 'Teacher',
                'emergency_contact_name' => 'Brian',
                'emergency_contact_phone' => '7485961545',
                'created_at' => '2025-07-15 05:56:01',
                'updated_at' => '2025-07-15 05:56:01',
            ),
        ));
    }
}
