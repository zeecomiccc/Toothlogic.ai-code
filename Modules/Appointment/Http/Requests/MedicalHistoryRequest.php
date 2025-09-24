<?php

namespace Modules\Appointment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicalHistoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'under_medical_treatment' => 'nullable|in:Yes,No',
            'treatment_details' => 'nullable|string',
            'hospitalized_last_year' => 'nullable|in:Yes,No',
            'hospitalization_reason' => 'nullable|string',
            'diseases' => 'nullable|array',
            'pregnant_or_breastfeeding' => 'nullable|string|in:Yes,No,N/A',
            'taking_medications' => 'nullable|array',
            'known_allergies' => 'nullable|array',
            // diseases, taking_medications, known_allergies are arrays but not nullable
        ];
    }
} 
