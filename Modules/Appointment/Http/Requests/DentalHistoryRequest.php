<?php

namespace Modules\Appointment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DentalHistoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'last_dental_visit_date' => 'nullable|date',
            'reason_for_last_visit' => 'nullable|string|max:255',
            'issues_experienced' => 'nullable|array',
            'dental_anxious' => 'nullable|in:Yes,No',
            'dental_anxiety_level' => 'nullable|integer|min:1|max:5',
        ];
    }
} 
