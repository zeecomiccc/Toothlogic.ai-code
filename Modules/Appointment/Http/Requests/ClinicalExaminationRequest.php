<?php

namespace Modules\Appointment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClinicalExaminationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'face_symmetry' => 'nullable|in:Normal,Abnormal',
            'tmj_status' => 'nullable|array',
            'occlusion_bite' => 'nullable|in:Normal,Malocclusion',
            'malocclusion_class.*' => 'string|in:Class I,Class II,Class III',
            'soft_tissue_status' => 'nullable|in:Normal,Abnormal',
            'teeth_status' => 'nullable|string',
            'gingival_health' => 'nullable|in:Healthy,Gingivitis,Periodontitis',
            'bleeding_on_probing' => 'nullable|in:Yes,No',
            'mobility' => 'nullable|in:Present,Absent',
            'bruxism' => 'nullable|in:Yes,No',
            // Other fields are optional
        ];
    }
}