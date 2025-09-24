<?php

namespace Modules\Appointment\Http\Controllers\Backend\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Appointment\Models\PatientEncounter;
use App\Models\Modules\Appointment\Models\OrthodonticTreatmentDailyRecord;
use Modules\Appointment\Transformers\EncounterResource;
use Modules\Appointment\Transformers\EncounterDetailsResource;
use PDF;
use Illuminate\Support\Facades\File;

class PatientEncounterController extends Controller
{
    public function encounterList(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $encounter_data = PatientEncounter::SetRole(auth()->user())->with('user', 'clinic', 'doctor', 'appointment', 'soap');

        if ($request->has('clinic_id') && ($request->clinic_id != '')) {
            $encounter_data->where('clinic_id', $request->clinic_id);
        }

        $encounter = $encounter_data->paginate($perPage);
        $encounterCollection = EncounterResource::collection($encounter);

        if ($request->filled('id')) {

            $encounter = $encounter_data->where('id', $request->id)->first();
            $responseData = new EncounterDetailsResource($encounter);

            return response()->json([
                'status' => true,
                'data' => $responseData,
                'message' => __('appointment.encounter_details'),
            ], 200);
        }

        return response()->json([
            'status' => true,
            'data' => $encounterCollection,
            'message' => __('appointment.encounter_list'),
        ], 200);
    }

    public function encounterInvoice(Request $request)
    {
        $id = $request->id;
        
        // Load PatientEncounter with all relationships including nested billingItem
        $data = PatientEncounter::where('id', $id)
            ->with([
                'user', 
                'clinic', 
                'doctor', 
                'medicalHistroy', 
                'prescriptions', 
                'EncounterOtherDetails', 
                'medicalReport', 
                'appointmentdetail', 
                'billingrecord.billingItem.clinicservice'
            ])
            ->first();

        if (!$data) {
            return response()->json(['status' => false, 'message' => 'Encounter not found'], 404);
        }

        $data['selectedProblemList'] =  $data->medicalHistroy()->where('type', 'encounter_problem')->get();
        $data['selectedObservationList'] = $data->medicalHistroy()->where('type', 'encounter_observations')->get();
        $data['notesList'] = $data->medicalHistroy()->where('type', 'encounter_notes')->get();
        $data['prescriptions'] = $data->prescriptions()->get();
        $data['other_details'] = $data->EncounterOtherDetails()->value('other_details') ?? null;
        $data['signature'] = optional(optional($data->doctor)->doctor)->Signature ?? null;

        // Ensure brand_mark_url is computed for the clinic
        if ($data->clinic) {
            // Force the accessor to be computed by manually getting the media URL
            $brandMarkUrl = $data->clinic->getFirstMediaUrl('brand_mark');
            $data->clinic->brand_mark_url = !empty($brandMarkUrl) ? $brandMarkUrl : null;
        }

        // Check if this is an estimate or invoice
        $is_estimate = optional($data->billingrecord)->is_estimate ?? false;
        
        // For estimates, redirect to the BillingRecordController::downloadBillingPDF method
        // since that's where the estimate template is designed to work properly
        if ($is_estimate && $data->billingrecord) {
            // Redirect to the billing record PDF download
            $billingRecordId = $data->billingrecord->id;
            return redirect()->route('backend.billing-record.download.pdf', ['id' => $billingRecordId]);
        }
        
        // For invoices, use the invoice template
        $template = "appointment::backend.encounter_template.invoice";
        $dataArray = $data->toArray();
        
        $pdf = PDF::loadHTML(view($template, ['data' => $dataArray])->render())
            ->setOptions(['defaultFont' => 'sans-serif']);
        $baseDirectory = storage_path('app/public');
        $highestDirectory = collect(File::directories($baseDirectory))->map(function ($directory) {
            return basename($directory);
        })->max() ?? 0;
        $nextDirectory = intval($highestDirectory) + 1;
        while (File::exists($baseDirectory . '/' . $nextDirectory)) {
            $nextDirectory++;
        }
        $newDirectory = $baseDirectory . '/' . $nextDirectory;
        File::makeDirectory($newDirectory, 0777, true);

        $filename = 'invoice_' . $id . '.pdf';
        $filePath = $newDirectory . '/' . $filename;

        $pdf->save($filePath);

        $url = url('storage/' . $nextDirectory . '/' . $filename);
        return response()->json(['status' => true, 'link' => $url], 200);
    }

    // public function downloadPrescription(Request $request)
    // {
    //     $id = $request->id;

    //     $data = PatientEncounter::where('id', $id)->with('user', 'clinic', 'doctor', 'medicalHistroy', 'prescriptions', 'EncounterOtherDetails', 'medicalReport', 'appointmentdetail', 'billingrecord')->first();


    //     $data['prescriptions'] = $data->prescriptions()->get();
    //     $data['signature'] = optional(optional($data->doctor)->doctor)->Signature ?? null;

    //     $pdf = PDF::loadHTML(view("appointment::backend.encounter_template.prescription", ['data' => $data])->render())
    //         ->setOptions(['defaultFont' => 'sans-serif']);

    //     $baseDirectory = storage_path('app/public');
    //     $highestDirectory = collect(File::directories($baseDirectory))->map(function ($directory) {
    //         return basename($directory);
    //     })->max() ?? 0;
    //     $nextDirectory = intval($highestDirectory) + 1;
    //     while (File::exists($baseDirectory . '/' . $nextDirectory)) {
    //         $nextDirectory++;
    //     }
    //     $newDirectory = $baseDirectory . '/' . $nextDirectory;
    //     File::makeDirectory($newDirectory, 0777, true);

    //     $filename = 'prescription_' . $id . '.pdf';
    //     $filePath = $newDirectory . '/' . $filename;

    //     $pdf->save($filePath);

    //     $url = url('storage/' . $nextDirectory . '/' . $filename);

    //     return response()->json(['status' => true, 'link' => $url], 200);

    // }

    public function downloadPrescription(Request $request)
    {
        $id = $request->id;

        $data = PatientEncounter::with([
            'user',
            'user.cities',
            'user.states',
            'user.countries',
            'clinic',
            'clinic.cities',
            'clinic.states', 
            'clinic.countries',
            'doctor',
            'doctor.doctor',
            'medicalHistroy',
            'prescriptions',
            'EncounterOtherDetails',
            'medicalReport',
            'appointmentdetail',
            'billingrecord'
        ])->findOrFail($id);

        $data['prescriptions'] = $data->prescriptions()->get();
        $data['signature'] = optional(optional($data->doctor)->doctor)->Signature ?? null;

        // Ensure brand_mark_url is computed for the clinic
        if ($data->clinic) {
            // Force the accessor to be computed by manually getting the media URL
            $brandMarkUrl = $data->clinic->getFirstMediaUrl('brand_mark');
            $data->clinic->brand_mark_url = !empty($brandMarkUrl) ? $brandMarkUrl : null;
        }

        $pdf = PDF::loadHTML(view("appointment::backend.encounter_template.prescription", ['data' => $data])->render())
            ->setOptions(['defaultFont' => 'sans-serif']);

        $filename = 'prescription_' . $id . '.pdf';

        // 👉 Detect API call
        if ($request->wantsJson() || $request->is('api/*')) {
            $pdfContent = $pdf->output(); // raw binary

            return response()->json([
                'status' => true,
                'filename' => $filename,
                'mime_type' => 'application/pdf',
                'base64' => base64_encode($pdfContent),
            ]);
        }

        // 👉 Detect AJAX call from frontend expecting a download link
        if ($request->ajax()) {
            $pdfPath = storage_path("app/public/tmp");
            if (!File::exists($pdfPath)) {
                File::makeDirectory($pdfPath, 0777, true);
            }

            $filePath = $pdfPath . '/' . $filename;
            $pdf->save($filePath);

            $downloadLink = asset('storage/tmp/' . $filename);
            return response()->json([
                'status' => true,
                'link' => $downloadLink
            ]);
        }

        // 👉 Default: browser (direct download)
        // return $pdf->stream($filename);
        return $pdf->download($filename);
    }

    public function downloadOrthoDailyRecordsPDF(Request $request)
    {
        $id = $request->id;

        $data = PatientEncounter::with([
            'user',
            'clinic',
            'doctor',
            'medicalHistroy',
            'prescriptions',
            'EncounterOtherDetails',
            'medicalReport',
            'appointmentdetail',
            'billingrecord',
            'orthodonticDailyRecords',
        ])->findOrFail($id);

        // Load the orthodontic daily records separately to ensure they are available
        $orthodontic_daily_records = OrthodonticTreatmentDailyRecord::where('encounter_id', $id)->get();
        
        $data['dailyRecords'] = $orthodontic_daily_records;
        $data['signature'] = optional(optional($data->doctor)->doctor)->Signature ?? null;

        $pdf = PDF::loadHTML(view("appointment::backend.encounter_template.orthodontic_treatment_daily_record_pdf", [
            'data' => $data,
            'dailyRecords' => $orthodontic_daily_records,
        ])->render())
            ->setOptions(['defaultFont' => 'sans-serif']);

        $filename = 'ortho_daily_records_' . $id . '.pdf';

        // 👉 Detect API call
        if ($request->wantsJson() || $request->is('api/*')) {
            $pdfContent = $pdf->output(); // raw binary

            return response()->json([
                'status' => true,
                'filename' => $filename,
                'mime_type' => 'application/pdf',
                'base64' => base64_encode($pdfContent),
            ]);
        }

        // 👉 Detect AJAX call from frontend expecting a download link
        if ($request->ajax()) {
            $pdfPath = storage_path("app/public/tmp");
            if (!File::exists($pdfPath)) {
                File::makeDirectory($pdfPath, 0777, true);
            }

            $filePath = $pdfPath . '/' . $filename;
            $pdf->save($filePath);

            $downloadLink = asset('storage/tmp/' . $filename);
            return response()->json([
                'status' => true,
                'link' => $downloadLink
            ]);
        }

        // 👉 Default: browser (direct download)
        return $pdf->download($filename);
    }
}
