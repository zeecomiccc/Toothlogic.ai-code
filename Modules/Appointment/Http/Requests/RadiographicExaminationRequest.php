<?php

namespace Modules\Appointment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RadiographicExaminationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'radiograph_type' => 'nullable|array',
            'radiograph_findings' => 'nullable|string|max:1000',
        ];
    }
}