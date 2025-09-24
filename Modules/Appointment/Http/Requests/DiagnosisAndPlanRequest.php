<?php

namespace Modules\Appointment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiagnosisAndPlanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'diagnosis' => 'nullable|string|max:1000',
            'proposed_treatments' => 'nullable|array',
            'planned_timeline' => 'nullable|string|max:255',
            'alternatives_discussed' => 'nullable|in:Yes,No',
            'risks_explained' => 'nullable|in:Yes,No',
            'questions_addressed' => 'nullable|in:Yes,No',
            // proposed_treatments is an array, not nullable
        ];
    }
} 