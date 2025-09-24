<?php

namespace Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Appointment\Database\factories\JawTreatmentFactory;

class JawTreatment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['patient_history_id', 'upper_jaw_treatment', 'lower_jaw_treatment'];

    public function patientHistory()
    {
        return $this->belongsTo(\Modules\Appointment\Models\PatientHistory::class, 'patient_history_id');
    }
}
