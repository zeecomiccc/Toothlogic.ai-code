<?php

namespace Modules\Appointment\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class FollowUpNote extends BaseModel
{
    use SoftDeletes;

    protected $table = 'follow_up_notes';

    protected $fillable = [
        'clinic_id',
        'doctor_id',
        'patient_id',
        'encounter_id',
        'title',
        'description',
        'date',
        'reminder_sent',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function doctor()
    {
        return $this->belongsTo(\App\Models\User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(\App\Models\User::class, 'patient_id');
    }

    public function clinic()
    {
        return $this->belongsTo(\Modules\Clinic\Models\Clinics::class, 'clinic_id');
    }

    public function receptionists()
    {
        return $this->hasManyThrough(
            \App\Models\User::class,
            \Modules\Clinic\Models\Receptionist::class,
            'clinic_id', // Foreign key on receptionist table
            'id', // Foreign key on users table
            'clinic_id', // Local key on follow_up_notes table
            'receptionist_id' // Local key on receptionist table
        )->role('receptionist');
    }
}
