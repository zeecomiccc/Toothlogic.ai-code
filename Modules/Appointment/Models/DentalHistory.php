<?php

namespace Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Appointment\Database\factories\DentalHistoryFactory;

class DentalHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'patient_history_id', 'last_dental_visit_date',
        'reason_for_last_visit', 'issues_experienced', 'dental_anxiety_level'
    ];

    protected $casts = [
        'issues_experienced' => 'array',
        'dental_anxiety_level' => 'integer',
    ];

    public function history()
    {
        return $this->belongsTo(PatientHistory::class, 'patient_history_id');
    }
    
    
}
