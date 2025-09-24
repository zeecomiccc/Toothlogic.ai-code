<?php

namespace Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Appointment\Database\factories\ClinicalExaminationFactory;

class ClinicalExamination extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'patient_history_id', 'face_symmetry', 'tmj_status', 'soft_tissue_status',
        'teeth_status', 'gingival_health', 'bleeding_on_probing', 'pocket_depths',
        'mobility','occlusion_bite', 'malocclusion_class', 'bruxism'
    ];

    protected $casts = [
        'tmj_status' => 'array',
        'malocclusion_class' => 'array',
        'bleeding_on_probing' => 'boolean',
        'mobility' => 'boolean',
        'bruxism' => 'boolean',
    ];

    public function history()
    {
        return $this->belongsTo(PatientHistory::class, 'patient_history_id');
    }
    
   
}
