<?php

namespace Modules\Appointment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FollowUpNoteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'clinic_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'patient_id' => 'required|integer',
            'encounter_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
        ];
    }
}
