<?php

namespace Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Appointment\Database\factories\PatientHistoryFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Clinic\Models\Clinics;

class PatientHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['patient_id', 'encounter_id'];

    public function demographic()
    {
        return $this->hasOne(PatientDemographic::class);
    }
    public function medicalHistory()
    {
        return $this->hasOne(MedicalHistory::class);
    }
    public function dentalHistory()
    {
        return $this->hasOne(DentalHistory::class);
    }
    public function chiefComplaint()
    {
        return $this->hasOne(ChiefComplaint::class);
    }
    public function clinicalExamination()
    {
        return $this->hasOne(ClinicalExamination::class);
    }
    public function radiographicExamination()
    {
        return $this->hasOne(RadiographicExamination::class);
    }
    public function diagnosisAndPlan()
    {
        return $this->hasOne(DiagnosisAndPlan::class);
    }
    public function jawTreatment()
    {
        return $this->hasOne(JawTreatment::class);
    }
    public function informedConsent()
    {
        return $this->hasOne(InformedConsent::class);
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'patient_id');
    }

    public function encounter()
    {
        return $this->belongsTo(PatientEncounter::class, 'encounter_id');
    }
}
