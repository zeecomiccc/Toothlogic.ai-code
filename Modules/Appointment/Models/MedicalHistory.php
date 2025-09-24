<?php

namespace Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Appointment\Database\factories\MedicalHistoryFactory;

class MedicalHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'patient_history_id', 'under_medical_treatment', 'treatment_details',
        'hospitalized_last_year', 'hospitalization_reason',
        'pregnant_or_breastfeeding', 'taking_medications', 'known_allergies', 'diseases'
    ];

    protected $casts = [
        'diseases' => 'array',
        'taking_medications' => 'array',
        'known_allergies' => 'array',
        'under_medical_treatment' => 'boolean',
        'hospitalized_last_year' => 'boolean',
    ];

    public function history()
    {
        return $this->belongsTo(PatientHistory::class, 'patient_history_id');
    }
    
    
}
