<?php

namespace Modules\Appointment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostInstructions extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'procedure_type',
        'post_instructions',
    ];

    /**
     * Scope to get instructions by procedure type
     */
    public function scopeByProcedureType($query, $type)
    {
        return $query->where('procedure_type', $type);
    }

    /**
     * Get all procedure types
     */
    public static function getProcedureTypes()
    {
        return [
            'tooth_extraction' => 'Tooth Extraction',
            'dental_fillings' => 'Dental Fillings',
            'root_canal_treatment' => 'Root Canal Treatment',
            'crowns_bridges' => 'Crowns and Bridges',
            'dental_implants' => 'Dental Implants',
            'scaling_root_planing' => 'Scaling and Root Planing (Deep Cleaning)',
            'braces' => 'Braces (Fixed Orthodontic Treatment)',
            'clear_aligners' => 'Clear Aligners',
            'contact_dentist' => 'When to Contact Your Dentist'
        ];
    }
}
