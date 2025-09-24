<?php

namespace Modules\Appointment\Services;

use Modules\Appointment\Models\PatientHistory;
use Modules\Appointment\Models\PatientDemographic;
use Modules\Appointment\Models\MedicalHistory;
use Modules\Appointment\Models\DentalHistory;
use Modules\Appointment\Models\ChiefComplaint;
use Modules\Appointment\Models\ClinicalExamination;
use Modules\Appointment\Models\RadiographicExamination;
use Modules\Appointment\Models\DiagnosisAndPlan;
use Modules\Appointment\Models\JawTreatment;
use Modules\Appointment\Models\InformedConsent;

class PatientHistoryService
{
    // Step 1: Demographic
    public function saveDemographic(array $data)
    {
        // Map form fields to DB columns (now using backend keys directly)
        $mapped = [
            'id' => $data['id'] ?? null,
            'patient_history_id' => $data['patient_history_id'] ?? null,
            'full_name' => $data['full_name'] ?? null,
            'dob' => $data['dob'] ?? null,
            'gender' => $data['gender'] ?? null,
            'address' => $data['address'] ?? null,
            'phone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'occupation' => $data['occupation'] ?? null,
        ];

        // Split emergency contact if needed
        if (!empty($data['emergency_contact'])) {
            $parts = preg_split('/\s+/', $data['emergency_contact']);
            $mapped['emergency_contact_phone'] = array_pop($parts);
            $mapped['emergency_contact_name'] = implode(' ', $parts);
        }

        $demographic = PatientDemographic::updateOrCreate(
            ['patient_history_id' => $mapped['patient_history_id']],
            $mapped
        );
        return $demographic;
    }

    public function getDemographic($patient_history_id)
    {
        return PatientDemographic::where('patient_history_id', $patient_history_id)->first();
    }

    // Step 2: Medical History
    public function saveMedicalHistory(array $data)
    {
        
        $mapped = [
            'id' => $data['id'] ?? null,
            'patient_history_id' => $data['patient_history_id'] ?? null,
            'under_medical_treatment' => ($data['under_medical_treatment'] ?? '') === 'Yes' ? 1 : 0,
            'treatment_details' => $data['treatment_details'] ?? null,
            'hospitalized_last_year' => ($data['hospitalized_last_year'] ?? '') === 'Yes' ? 1 : 0,
            'hospitalization_reason' => $data['hospitalization_reason'] ?? null,
            'diseases' => $data['diseases'] ?? null,
            'pregnant_or_breastfeeding' => $data['pregnant_or_breastfeeding'] ?? null,
            'taking_medications' => $data['taking_medications'] ?? null,
            'known_allergies' => $data['known_allergies'] ?? null,
        ];
        $medical = MedicalHistory::updateOrCreate(
            ['patient_history_id' => $mapped['patient_history_id']],
            $mapped
        );
        return $medical;
    }

    public function getMedicalHistory($patient_history_id)
    {
        $row = MedicalHistory::where('patient_history_id', $patient_history_id)->first();
        $result = $row ? $row->toArray() : [];
      
        return $result;
    }

    // Step 3: Dental History
    public function saveDentalHistory(array $data)
    {
        $mapped = [
            'id' => $data['id'] ?? null,
            'patient_history_id' => $data['patient_history_id'] ?? null,
            'last_dental_visit_date' => $data['last_dental_visit_date'] ?? null,
            'reason_for_last_visit' => $data['reason_for_last_visit'] ?? null,
            'issues_experienced' => $data['issues_experienced'] ?? null,
            'dental_anxiety_level' => $data['dental_anxiety_level'] ?? null,
        ];
        if (isset($mapped['issues_experienced']) && is_array($mapped['issues_experienced'])) {
            $mapped['issues_experienced'] = json_encode($mapped['issues_experienced']);
        }
        $dental = DentalHistory::updateOrCreate(
            ['patient_history_id' => $mapped['patient_history_id']],
            $mapped
        );
        return $dental;
    }

    public function getDentalHistory($patient_history_id)
    {
        $row = DentalHistory::where('patient_history_id', $patient_history_id)->first();
        $result = $row ? $row->toArray() : [];
        
        return $result;
    }

    // Step 4: Chief Complaint
    public function saveChiefComplaint(array $data)
    {
        $mapped = [
            'id' => $data['id'] ?? null,
            'patient_history_id' => $data['patient_history_id'] ?? null,
            'complaint_notes' => $data['complaint_notes'] ?? null,
        ];
        $chief = ChiefComplaint::updateOrCreate(
            ['patient_history_id' => $mapped['patient_history_id']],
            $mapped
        );
        return $chief;
    }

    public function getChiefComplaint($patient_history_id)
    {
        return ChiefComplaint::where('patient_history_id', $patient_history_id)->first();
    }

    // Step 5: Clinical Examination
    public function saveClinicalExamination(array $data)
    {
        // Debug: Log the incoming data
        \Log::info('Clinical Examination Data:', $data);
    
        $mapped = [
            'id' => $data['id'] ?? null,
            'patient_history_id' => $data['patient_history_id'] ?? null,
            'face_symmetry' => $data['face_symmetry'] ?? null,
            'tmj_status' => $data['tmj_status'] ?? null,
            'occlusion_bite' => $data['occlusion_bite'] ?? null,
            'soft_tissue_status' => $data['soft_tissue_status'] ?? null,
            'teeth_status' => $data['teeth_status'] ?? null,
            'gingival_health' => $data['gingival_health'] ?? null,
            'bleeding_on_probing' => ($data['bleeding_on_probing'] ?? '') === 'Yes' ? 1 : 0,
            'pocket_depths' => $data['pocket_depths'] ?? null,
            'mobility' => ($data['mobility'] ?? '') === 'Present' ? 1 : 0,
            'malocclusion_class' => $data['malocclusion_class'] ?? null,
            'bruxism' => ($data['bruxism'] ?? '') === 'Yes' ? 1 : 0,
        ];
        
        // Debug: Log the mapped data
        \Log::info('Mapped Clinical Examination Data:', $mapped);
        
        $clinical = ClinicalExamination::updateOrCreate(
            ['patient_history_id' => $mapped['patient_history_id']],
            $mapped
        );
        return $clinical;
    }

    public function getClinicalExamination($patient_history_id)
    {
        $row = ClinicalExamination::where('patient_history_id', $patient_history_id)->first();
        $result = $row ? $row->toArray() : [];
        
        return $result;
    }

    // Step 6: Radiographic Examination
    public function saveRadiographicExamination(array $data)
    {
        $mapped = [
            'id' => $data['id'] ?? null,
            'patient_history_id' => $data['patient_history_id'] ?? null,
            'radiograph_type' => $data['radiograph_type'] ?? null,
            'radiograph_findings' => $data['radiograph_findings'] ?? null,
        ];
        $radio = RadiographicExamination::updateOrCreate(
            ['patient_history_id' => $mapped['patient_history_id']],
            $mapped
        );
        return $radio;
    }

    public function getRadiographicExamination($patient_history_id)
    {
        $row = RadiographicExamination::where('patient_history_id', $patient_history_id)->first();
        $result = $row ? $row->toArray() : [];
        return $result;
    }

    // Step 7: Diagnosis and Plan
    public function saveDiagnosisAndPlan(array $data)
    {
        $mapped = [
            'id' => $data['id'] ?? null,
            'patient_history_id' => $data['patient_history_id'] ?? null,
            'diagnosis' => $data['diagnosis'] ?? null,
            'proposed_treatments' => $data['proposed_treatments'] ?? null,
            'planned_timeline' => $data['planned_timeline'] ?? null,
            'alternatives_discussed' => ($data['alternatives_discussed'] ?? '') === 'Yes' ? 1 : 0,
            'risks_explained' => ($data['risks_explained'] ?? '') === 'Yes' ? 1 : 0,
            'questions_addressed' => ($data['questions_addressed'] ?? '') === 'Yes' ? 1 : 0,
            'upper_jaw_treatment' => $data['upper_jaw_treatment'] ?? null,
            'lower_jaw_treatment' => $data['lower_jaw_treatment'] ?? null,
        ];
        if (isset($mapped['proposed_treatments']) && is_array($mapped['proposed_treatments'])) {
            $mapped['proposed_treatments'] = json_encode($mapped['proposed_treatments']);
        }
        $diag = DiagnosisAndPlan::updateOrCreate(
            ['patient_history_id' => $mapped['patient_history_id']],
            $mapped
        );
        return $diag;
    }

    public function getDiagnosisAndPlan($patient_history_id)
    {
        $row = DiagnosisAndPlan::where('patient_history_id', $patient_history_id)->first();
        $result = $row ? $row->toArray() : [];
       
        return $result;
    }

    // Step 8: Dental Chart (Jaw Treatments)
    public function saveDentalChart(array $data)
    {
        // Debug: Log the incoming data
        \Log::info('PatientHistoryService saveDentalChart data:', $data);
        
        // Check if we have the required data
        if (empty($data['patient_history_id'])) {
            \Log::error('Patient history ID is missing in dental chart data');
            return false;
        }
        
        try {
            // Check if the table exists
            if (!\Schema::hasTable('jaw_treatments')) {
                \Log::error('jaw_treatments table does not exist');
                return false;
            }
            
            // Check if the patient_history_id exists in patient_histories table
            $patientHistoryExists = \DB::table('patient_histories')->where('id', $data['patient_history_id'])->exists();
            if (!$patientHistoryExists) {
                \Log::error('Patient history with ID ' . $data['patient_history_id'] . ' does not exist');
                return false;
            }
            
            \Log::info('Attempting to save dental chart data...');
            
            $jawTreatment = \Modules\Appointment\Models\JawTreatment::updateOrCreate(
                [
                    'patient_history_id' => $data['patient_history_id']
                ],
                [
                    'upper_jaw_treatment' => $data['upper_jaw_treatment'] ?? null,
                    'lower_jaw_treatment' => $data['lower_jaw_treatment'] ?? null
                ]
            );
            
            // Debug: Log the result
            \Log::info('JawTreatment created/updated successfully:', $jawTreatment->toArray());
            
            return $jawTreatment;
            
        } catch (\Exception $e) {
            \Log::error('Error saving dental chart data: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    public function getDentalChart($patient_history_id)
    {
        $jawTreatment = \Modules\Appointment\Models\JawTreatment::where('patient_history_id', $patient_history_id)->first();
        return [
            'upper_jaw_treatment' => $jawTreatment ? $jawTreatment->upper_jaw_treatment : null,
            'lower_jaw_treatment' => $jawTreatment ? $jawTreatment->lower_jaw_treatment : null
        ];
    }

    // Step 9: Informed Consent
    public function saveInformedConsent(array $data)
    {
        $mapped = [
            'id' => $data['id'] ?? null,
            'patient_history_id' => $data['patient_history_id'] ?? null,
            'patient_signature' => $data['patient_signature'] ?? null,
            'dentist_signature' => $data['dentist_signature'] ?? null,
        ];

        $informedConsent = InformedConsent::updateOrCreate(
            ['patient_history_id' => $mapped['patient_history_id']],
            $mapped
        );

        // Note: Patient history is NOT automatically marked complete here
        // The frontend will explicitly call markComplete when the user completes step 9

        return $informedConsent;
    }

    public function getInformedConsent($patient_history_id)
    {
        $informedConsent = InformedConsent::where('patient_history_id', $patient_history_id)->first();
        return $informedConsent ? $informedConsent->toArray() : [];
    }

    public function deletePatientHistory($id)
    {
        $history = PatientHistory::find($id);
        if ($history) {
            $history->delete();
            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully.'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete the record.'
        ], 400);
    }

    // Utility: encode array fields as JSON before saving
    // private function encodeArrayFields(array &$data, array $fields)
    // {
    //     foreach ($fields as $field) {
    //         if (isset($data[$field]) && is_array($data[$field])) {
    //             $data[$field] = json_encode($data[$field]);
    //         }
    //     }
    // }

    // Utility: decode JSON string fields to arrays
    // private function decodeArrayFields(array &$data, array $fields)
    // {
    //     foreach ($fields as $field) {
    //         if (isset($data[$field]) && is_string($data[$field]) && $data[$field] && $data[$field][0] === '[') {
    //             $decoded = json_decode($data[$field], true);
    //             if (json_last_error() === JSON_ERROR_NONE) {
    //                 $data[$field] = $decoded;
    //             }
    //         }
    //     }
    // }
} 