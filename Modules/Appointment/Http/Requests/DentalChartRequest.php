<?php

namespace Modules\Appointment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DentalChartRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'upper_jaw_treatment' => 'nullable|string|max:1000',
            'lower_jaw_treatment' => 'nullable|string|max:1000',
        ];
    }
} 