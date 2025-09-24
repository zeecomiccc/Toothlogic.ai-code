<?php

namespace Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Appointment\Database\factories\PatientDemographicFactory;

class PatientDemographic extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'patient_history_id', 'full_name', 'dob', 'gender', 'address',
        'phone', 'email', 'occupation', 'emergency_contact_name', 'emergency_contact_phone'
    ];

    public function history()
    {
        return $this->belongsTo(PatientHistory::class, 'patient_history_id');
    }
    
    
}
