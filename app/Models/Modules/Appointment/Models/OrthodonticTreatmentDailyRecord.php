<?php

namespace App\Models\Modules\Appointment\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrthodonticTreatmentDailyRecord extends BaseModel
{
    /** @use HasFactory<\Database\Factories\Modules\Appointment\Models\OrthodonticTreatmentDailyRecordFactory> */
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'doctor_id',
        'patient_id',
        'encounter_id',
        'date',
        'procedure_performed',
        'oral_hygiene_status',
        'patient_compliance',
        'next_appointment_date_procedure',
        'remarks_comments',
        'initials',
    ];

    // Relationships (stubs)
    public function clinic()
    {
        return $this->belongsTo(\Modules\Clinic\Models\Clinics::class, 'clinic_id');
    }
    public function doctor()
    {
        return $this->belongsTo(\Modules\Clinic\Models\Doctor::class, 'doctor_id');
    }
    public function patient()
    {
        return $this->belongsTo(\App\Models\User::class, 'patient_id');
    }
    public function encounter()
    {
        return $this->belongsTo(\Modules\Appointment\Models\PatientEncounter::class, 'encounter_id');
    }
}
