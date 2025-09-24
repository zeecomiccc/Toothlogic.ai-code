<?php

namespace Modules\Appointment\database\seeders;

use Illuminate\Database\Seeder;

class AppointmentDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('IS_DUMMY_DATA')) {
            $this->call(AppointmentsTableSeeder::class);
            $this->call(AppointmentTransactionsTableSeeder::class);
            $this->call(PatientEncounterSeeder::class);
            $this->call(InstallmentSeeder::class);
            // $this->call(AppointmentPatientRecordsTableSeeder::class);
            $this->call(AppointmentPatientBodychartTableSeeder::class);
            $this->call(PatientHistorySeeder::class);
            $this->call(PatientDemographicSeeder::class);
            $this->call(MedicalHistorySeeder::class);
            $this->call(DentalHistorySeeder::class);
            $this->call(ChiefComplaintSeeder::class);
            $this->call(ClinicalExaminationSeeder::class);
            $this->call(RadiographicExaminationSeeder::class);
            $this->call(DiagnosisAndPlanSeeder::class);
            $this->call(JawTreatmentSeeder::class);
            $this->call(PostInstructionSeeder::class);
            $this->call(InformedConsentSeeder::class);
            $this->call(LabReportSeeder::class);

        }
    }
}
