<?php

namespace Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Appointment\Database\factories\RadiographicExaminationFactory;

class RadiographicExamination extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'patient_history_id', 'radiograph_type', 'radiograph_findings'
    ];

    protected $casts = [
        'radiograph_type' => 'array',
    ];

    public function history()
    {
        return $this->belongsTo(PatientHistory::class, 'patient_history_id');
    }
    
    
}
