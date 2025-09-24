<?php

namespace Modules\Appointment\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Appointment\Services\PatientHistoryService;
use PDF;
use Modules\Appointment\Http\Requests\DemographicRequest;
use Modules\Appointment\Http\Requests\MedicalHistoryRequest;
use Modules\Appointment\Http\Requests\DentalHistoryRequest;
use Modules\Appointment\Http\Requests\ChiefComplaintRequest;
use Modules\Appointment\Http\Requests\ClinicalExaminationRequest;
use Modules\Appointment\Http\Requests\RadiographicExaminationRequest;
use Modules\Appointment\Http\Requests\DiagnosisAndPlanRequest;
use Modules\Appointment\Http\Requests\DentalChartRequest;
use Modules\Appointment\Http\Requests\InformedConsentRequest;

class PatientHistoryController extends Controller
{
    protected $patientHistoryService;

    public function __construct(PatientHistoryService $patientHistoryService)
    {
        $this->patientHistoryService = $patientHistoryService;
    }

    /**
     * Create a new PatientHistory record and return its ID
     */
    public function create(Request $request)
    {
        $history = new \Modules\Appointment\Models\PatientHistory();
        $history->user_id = $request->user_id;
        $history->encounter_id = $request->encounter_id;
        $history->is_complete = 0; // Ensure incomplete on creation
        $history->save();
        return response()->json(['id' => $history->id]);
    }

    public function findByEncounter($encounter_id)
    {
        // Try to find the latest incomplete history first
        $history = \Modules\Appointment\Models\PatientHistory::where('encounter_id', $encounter_id)
            ->where('is_complete', 0)
            ->orderByDesc('id')
            ->first();

        // If none found, return the latest (completed) one
        if (!$history) {
            $history = \Modules\Appointment\Models\PatientHistory::where('encounter_id', $encounter_id)
                ->orderByDesc('id')
                ->first();
        }

        return response()->json($history);
    }

    // Step 1: Demographic
    public function storeDemographic(DemographicRequest $request)
    {
        $result = $this->patientHistoryService->saveDemographic($request->all());
        return response()->json($result);
    }

    public function getDemographic($id)
    {
        $result = $this->patientHistoryService->getDemographic($id);
        return response()->json($result);
    }

    // Step 2: Medical History
    public function storeMedicalHistory(MedicalHistoryRequest $request)
    {
        $result = $this->patientHistoryService->saveMedicalHistory($request->all());
        return response()->json($result);
    }

    public function getMedicalHistory($id)
    {
        $result = $this->patientHistoryService->getMedicalHistory($id);
        // Ensure checkbox fields are arrays
        foreach (['diseases', 'taking_medications', 'known_allergies'] as $field) {
            if (isset($result[$field]) && is_string($result[$field]) && $result[$field] && $result[$field][0] === '[') {
                $decoded = json_decode($result[$field], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $result[$field] = $decoded;
                }
            }
        }
        return response()->json($result);
    }

    // Step 3: Dental History
    public function storeDentalHistory(DentalHistoryRequest $request)
    {
        $result = $this->patientHistoryService->saveDentalHistory($request->all());
        return response()->json($result);
    }

    public function getDentalHistory($id)
    {
        $result = $this->patientHistoryService->getDentalHistory($id);
        return response()->json($result);
    }

    // Step 4: Chief Complaint
    public function storeChiefComplaint(ChiefComplaintRequest $request)
    {
        $result = $this->patientHistoryService->saveChiefComplaint($request->all());
        return response()->json($result);
    }

    public function getChiefComplaint($id)
    {
        $result = $this->patientHistoryService->getChiefComplaint($id);
        return response()->json($result);
    }

    // Step 5: Clinical Examination
    public function storeClinicalExamination(ClinicalExaminationRequest $request)
    {
        $result = $this->patientHistoryService->saveClinicalExamination($request->all());
        return response()->json($result);
    }

    public function getClinicalExamination($id)
    {
        $result = $this->patientHistoryService->getClinicalExamination($id);
        return response()->json($result);
    }

    // Step 6: Radiographic Examination
    public function storeRadiographicExamination(RadiographicExaminationRequest $request)
    {
        $result = $this->patientHistoryService->saveRadiographicExamination($request->all());
        return response()->json($result);
    }

    public function getRadiographicExamination($id)
    {
        $result = $this->patientHistoryService->getRadiographicExamination($id);
        return response()->json($result);
    }

    // Step 7: Diagnosis and Plan
    public function storeDiagnosisAndPlan(DiagnosisAndPlanRequest $request)
    {
        $result = $this->patientHistoryService->saveDiagnosisAndPlan($request->all());
        return response()->json($result);
    }

    public function getDiagnosisAndPlan($id)
    {
        $result = $this->patientHistoryService->getDiagnosisAndPlan($id);
        return response()->json($result);
    }

    // Step 8: Dental Chart (Jaw Treatments)
    public function storeDentalChart(DentalChartRequest $request)
    {
        // Debug: Log the incoming request data
        \Log::info('Dental Chart Request Data:', $request->all());
        
        try {
            $result = $this->patientHistoryService->saveDentalChart($request->all());
            
            // Debug: Log the result
            \Log::info('Dental Chart Save Result:', ['result' => $result]);
            
            if ($result === false) {
                \Log::error('Failed to save dental chart data');
                return response()->json(['error' => 'Failed to save dental chart data.'], 500);
            }
            
            if ($result === null) {
                return response()->json(['error' => 'Diagnosis and Plan not found for this patient history.'], 404);
            }
            
            return response()->json(['success' => true, 'data' => $result]);
            
        } catch (\Exception $e) {
            \Log::error('Exception in storeDentalChart: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'An error occurred while saving dental chart data.'], 500);
        }
    }

    public function getDentalChart($patient_history_id)
    {
        $result = $this->patientHistoryService->getDentalChart($patient_history_id);
        return response()->json($result);
    }

    // Step 9: Informed Consent
    public function storeInformedConsent(InformedConsentRequest $request)
    {
        $result = $this->patientHistoryService->saveInformedConsent($request->all());
        return response()->json($result);
    }

    public function getInformedConsent($patient_history_id)
    {
        $result = $this->patientHistoryService->getInformedConsent($patient_history_id);
        return response()->json($result);
    }

    public function edit($id)
    {
        $history = \Modules\Appointment\Models\PatientHistory::with([
            'demographic',
            'medicalHistory',
            'dentalHistory',
            'chiefComplaint',
            'clinicalExamination',
            'radiographicExamination',
            'diagnosisAndPlan',
            'jawTreatment',
            'informedConsent'
        ])->find($id);

        if (!$history) {
            return response()->json(['status' => false, 'message' => 'Patient history not found.'], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $history
        ]);
    }

    /**
     * Mark patient history as complete
     */
    public function markComplete($id)
    {
        $history = \Modules\Appointment\Models\PatientHistory::findOrFail($id);
        $history->is_complete = 1;
        $history->save();
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $deleted = $this->patientHistoryService->deletePatientHistory($id);
        return response()->json(['success' => $deleted]);
    }

    /**
     * Show a print-friendly view of the patient history
     */
    public function printPatientHistory($id)
    {
        $history = \Modules\Appointment\Models\PatientHistory::with([
            'demographic',
            'medicalHistory',
            'dentalHistory',
            'chiefComplaint',
            'clinicalExamination',
            'radiographicExamination',
            'diagnosisAndPlan',
            'jawTreatment',
            'informedConsent',
            'user',
            'encounter.clinic',
            'encounter.user',
        ])->findOrFail($id);
        return view('appointment::backend.patient_encounter.component.patient_history_print', compact('history'));
    }

    /**
     * Download the patient history as a PDF
     */
    public function downloadPatientHistoryPDF($id)
    {
        $history = \Modules\Appointment\Models\PatientHistory::with([
            'demographic',
            'medicalHistory',
            'dentalHistory',
            'chiefComplaint',
            'clinicalExamination',
            'radiographicExamination',
            'diagnosisAndPlan',
            'jawTreatment',
            'informedConsent',
            'user',
            'encounter.clinic',
            'encounter.user',
        ])->findOrFail($id);
        $pdf = PDF::loadView('appointment::backend.patient_encounter.component.patient_history_print', compact('history'));
        return $pdf->download('patient_history_' . $id . '.pdf');
    }
}
