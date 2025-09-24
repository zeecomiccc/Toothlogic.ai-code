<?php

namespace Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Appointment\Database\factories\DiagnosisAndPlanFactory;

class DiagnosisAndPlan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'patient_history_id', 'diagnosis', 'proposed_treatments',
        'planned_timeline', 'alternatives_discussed',
        'risks_explained', 'questions_addressed'
    ];

    protected $casts = [
        'proposed_treatments' => 'array',
        'alternatives_discussed' => 'boolean',
        'risks_explained' => 'boolean',
        'questions_addressed' => 'boolean',
    ];

    protected $table = 'diagnoses_and_plans';

    public function history()
    {
        return $this->belongsTo(PatientHistory::class, 'patient_history_id');
    }
    
    
}
