<?php

use Illuminate\Support\Facades\Route;
use Modules\Appointment\Http\Controllers\Backend\AppointmentsController;
use Modules\Appointment\Http\Controllers\Backend\ClinicAppointmentController;
use Modules\Appointment\Http\Controllers\Backend\PatientEncounterController;
use Modules\Appointment\Http\Controllers\Backend\EncounterTemplateController;
use Modules\Appointment\Http\Controllers\Backend\BillingRecordController;
use Modules\Appointment\Http\Controllers\Backend\ProblemsController;
use Modules\Appointment\Http\Controllers\Backend\ObservationController;
use Modules\Clinic\Http\Controllers\ClinicesController;
use Modules\Clinic\Http\Controllers\ClinicsServiceController;
use Modules\Customer\Http\Controllers\Backend\CustomersController;
use Modules\Tax\Http\Controllers\Backend\TaxesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
 *
 * Backend Routes
 *
 * --------------------------------------------------------------------
 */

Route::group(['prefix' => 'app', 'as' => 'backend.', 'middleware' => ['auth', 'auth_check']], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     */

    /*
     *
     *  Backend Appointments Routes
     *
     * ---------------------------------------------------------------------
     */

    Route::group(['prefix' => 'appointment', 'as' => 'appointment.'], function () {
        // // Route::get("index_list", [AppointmentsController::class, 'index_list'])->name("index_list");
        // // Route::get("index_data", [AppointmentsController::class, 'index_data'])->name("index_data");
        // // Route::get('export', [AppointmentsController::class, 'export'])->name('export');
        // // Route::post('/update-status/{id}', [AppointmentsController::class, 'updateStatus'])->name('updateStatus');
        // // Route::post('/update-payment-status/{id}', [AppointmentsController::class, 'updatePaymentStatus'])->name('updatePaymentStatus');
        // // Route::get('patient_list', [AppointmentsController::class, 'patient_list'])->name('patient_list');
        // // Route::get('view', [AppointmentsController::class, 'view'])->name('view');
        Route::post('save-payment', [AppointmentsController::class, 'savePayment'])->name('save_payment');
        Route::post('other-patient', [AppointmentsController::class, 'otherpatient'])->name('other_patient');
        Route::get('other-patientlist', [AppointmentsController::class, 'otherpatientlist'])->name('other_patientlist');
        // Route::post('/update-status/{id}', [AppointmentsController::class, 'updateStatus'])->name('updateStatus');
    });

    Route::resource("appointment", AppointmentsController::class);

    Route::group(['prefix' => 'appointments', 'as' => 'appointments.'], function () {
        Route::get("index_list", [ClinicAppointmentController::class, 'index_list'])->name("index_list");
        Route::get("index_data", [ClinicAppointmentController::class, 'index_data'])->name("index_data");
        Route::get('export', [ClinicAppointmentController::class, 'export'])->name('export');
        Route::get('view', [AppointmentsController::class, 'view'])->name('view');
        Route::post('/update-status/{id}', [AppointmentsController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/update-payment-status/{id}', [AppointmentsController::class, 'updatePaymentStatus'])->name('updatePaymentStatus');
        Route::put("appointment_patient/{id}", [ClinicAppointmentController::class, 'appointment_patient'])->name("appointment_patient");
        Route::get("{id}/appointment_patient_data", [ClinicAppointmentController::class, 'appointment_patient_data'])->name("appointment_patient_data");
        Route::put("appointment_bodychart/{id}", [ClinicAppointmentController::class, 'appointment_bodychart'])->name("appointment_bodychart");
        Route::get("{id}/appointment_bodychart_data", [ClinicAppointmentController::class, 'appointment_bodychart_data'])->name("appointment_bodychart ");
        Route::get('patient_list', [ClinicAppointmentController::class, 'patient_list'])->name('patient_list');
        Route::get('patient_list.export', [ClinicAppointmentController::class, 'patientListExport'])->name('patient_list.export');
        Route::get('appointment-details/{id}', [ClinicAppointmentController::class, 'patientDeatails'])->name('patientDeatails');
        Route::get('{id}/appointment_patient_data', [ClinicAppointmentController::class, 'appointment_patient_data']);
        Route::post('appointment_patient/{id}', [ClinicAppointmentController::class, 'appointment_patient'])->name('appointments.appointment_patient');

        Route::get("index_patientdata", [ClinicAppointmentController::class, 'index_patientdata'])->name("index_patientdata");
        Route::get('clinicAppointmentDetail/{id}', [ClinicAppointmentController::class, 'appointmentDetail'])->name('clinicAppointmentDetail');
        Route::get('patient_list/{id}', [ClinicAppointmentController::class, 'patientDeatails'])->name('patientDeatails');
        Route::get('invoice_detail', [ClinicAppointmentController::class, 'invoice_detail'])->name('invoice_detail');
        Route::get('download_invoice', [ClinicAppointmentController::class, 'downloadPDf'])->name('download_invoice');
        Route::post('bulk-action', [ClinicAppointmentController::class, 'bulk_action'])->name('bulk_action');
        Route::post('import', [ClinicAppointmentController::class, 'import'])->name('import');
        Route::get('download-sample/{type}', [ClinicAppointmentController::class, 'downloadSample'])->name('download-sample');
    });

    Route::get('post-instructions', [AppointmentsController::class, 'postInstructions'])->name('post-instructions');
    Route::post('update/post-instructions', [AppointmentsController::class, 'updatePostInstructions'])->name('update-post-instructions');

    Route::group(['prefix' => 'bodychart', 'as' => 'bodychart.'], function () {
        Route::get('bodychart_datatable/{id}', [ClinicAppointmentController::class, 'bodychart_datatable'])->name("bodychart_datatable");
        Route::delete('bodychartdestroy/{id}', [ClinicAppointmentController::class, 'bodychartdestroy'])->name("bodychartdestroy");
        Route::get("bodychart_image_list", [ClinicAppointmentController::class, 'bodychart_image_list'])->name("patient-record");
        Route::put("bodychart_form/appointment_bodychart/{id}", [ClinicAppointmentController::class, 'appointment_bodychart'])->name("appointment_bodychart");
        Route::get("bodychart_form/{id}/appointment_bodychart_data", [ClinicAppointmentController::class, 'appointment_bodychart_data'])->name("appointment_bodychart ");
        Route::get("bodychart_form/{id}/bodychart_templatedata", [ClinicAppointmentController::class, 'bodychart_templatedata'])->name("bodychart_templatedata");
        Route::get("bodychart_form/bodychart_image_list", [ClinicAppointmentController::class, 'bodychart_image_list'])->name("patient-record");
        Route::get('bodychart_form/{id}', [ClinicAppointmentController::class, 'bodychart_form'])->name("bodychart_form");
        Route::put("editbodychartview/appointment_upadtebodychart/{id}", [ClinicAppointmentController::class, 'appointment_upadtebodychart'])->name("appointment_upadtebodychart");
        Route::get("editbodychartview/{id}/bodychart_templatedata", [ClinicAppointmentController::class, 'bodychart_templatedata'])->name("bodychart_templatedata");
        Route::get("editbodychartview/{id}/appointment_bodychart_data", [ClinicAppointmentController::class, 'appointment_bodychart_data'])->name("appointment_bodychart ");
        Route::get("editbodychartview/bodychart_image_list", [ClinicAppointmentController::class, 'bodychart_image_list'])->name("patient-record");
        Route::get('editbodychartview/{id}', [ClinicAppointmentController::class, 'editbodychartview'])->name("editbodychartview");
        Route::post('bodychart-bulk-action', [ClinicAppointmentController::class, 'bodychart_bulk_action'])->name('bodychart_bulk_action');
        Route::get('get-bodychart-details/{id}', [ClinicAppointmentController::class, 'getBodychartDetail'])->name('get_bodychart_details');
    });
    Route::get('google_connect', [AppointmentsController::class, 'joinGoogleMeet'])->name('google_connect');
    Route::get('zoom_connect', [AppointmentsController::class, 'joinZoomMeet'])->name('zoom_connect');
    Route::get('callback', [AppointmentsController::class, 'Callback']);
    Route::get("patient-record", [ClinicAppointmentController::class, 'patient_record'])->name("patient-record");
    Route::get("bodychart/{id}", [ClinicAppointmentController::class, 'bodychart'])->name("bodychart");
    Route::resource("appointments", ClinicAppointmentController::class);

    Route::group(['prefix' => 'encounter', 'as' => 'encounter.'], function () {
        Route::get("index_list", [PatientEncounterController::class, 'index_list'])->name("index_list");
        Route::get("index_data", [PatientEncounterController::class, 'index_data'])->name("index_data");
        Route::get('export', [PatientEncounterController::class, 'export'])->name('export');
        Route::get('encounter-detail/{id}', [PatientEncounterController::class, 'encounterDetail'])->name('encounter_detail');
        Route::post('save-select-option', [PatientEncounterController::class, 'saveSelectOption'])->name('save_select_option');
        Route::get('remove-histroy-data', [PatientEncounterController::class, 'removeHistroyData']);
        Route::post('save-prescription', [PatientEncounterController::class, 'savePrescription']);
        Route::get('edit-prescription/{id}', [PatientEncounterController::class, 'editPrescription']);
        Route::post('update-prescription/{id}', [PatientEncounterController::class, 'updatePrescription']);
        Route::get('delete-prescription/{id}', [PatientEncounterController::class, 'deletePrescription']);
        Route::post('save-other-details', [PatientEncounterController::class, 'saveOtherDetails']);
        Route::post('save-medical-report', [PatientEncounterController::class, 'saveMedicalReport']);
        Route::get('edit-medical-report/{id}', [PatientEncounterController::class, 'editMedicalReport']);
        Route::post('update-medical-report/{id}', [PatientEncounterController::class, 'updateMedicalReport']);
        Route::get('delete-medical-report/{id}', [PatientEncounterController::class, 'deleteMedicalReport']);
        Route::get('get_report_data', [PatientEncounterController::class, 'GetReportData']);
        Route::get('send-medical-report', [PatientEncounterController::class, 'SendMedicalReport']);
        Route::get('send-prescription', [PatientEncounterController::class, 'sendPrescription']);
        Route::post('import-prescription', [PatientEncounterController::class, 'importPrescription']);
        Route::post('export-prescription', [PatientEncounterController::class, 'exportPrescriptionData']);
        Route::post('save-encounter-details', [PatientEncounterController::class, 'saveEncouterDetails'])->name('save-encounter-details');
        Route::post('save-encounter', [PatientEncounterController::class, 'saveEncouter'])->name('save-encounter');
        Route::get('download-encounterinvoice', [Modules\Appointment\Http\Controllers\Backend\API\PatientEncounterController::class, 'encounterInvoice']);

        // Route::get('download-prescription', [Modules\Appointment\Http\Controllers\Backend\API\PatientEncounterController::class, 'downloadPrescription']);
        Route::get('download-prescription', [Modules\Appointment\Http\Controllers\Backend\PatientEncounterController::class, 'downloadPrescription']);
        Route::get('get-medical-report-images/{id}', [\Modules\Appointment\Http\Controllers\Backend\PatientEncounterController::class, 'getMedicalReportImages']);
        Route::get('view-medical-report-file/{id}/{fileId}', [\Modules\Appointment\Http\Controllers\Backend\PatientEncounterController::class, 'viewMedicalReportFile'])->name('view-medical-report-file');
        Route::get('view-stl-file/{id}/{fileId}', [PatientEncounterController::class, 'viewStlFile'])->name('encounter.view-stl-file');


        Route::get('encounter-detail-page/{id}', [PatientEncounterController::class, 'EncouterDetailPage'])->name('encounter-detail-page');
        Route::get('encounter-detail-page/services/index_list', [ClinicsServiceController::class, 'index_list'])->name("index_list");
        Route::get('encounter-detail-page/clinics/index_list', [ClinicesController::class, 'index_list'])->name('index_list');
        Route::get('encounter-detail-page/customers/index_list', [CustomersController::class, 'index_list'])->name('index_list');
        Route::get('encounter-detail-page/tax/index_list', [TaxesController::class, 'index_list'])->name('index_list');

        Route::get('get-template-data/{id}', [PatientEncounterController::class, 'getTemplateData'])->name('get-template-data');
        Route::post('bulk-action', [PatientEncounterController::class, 'bulk_action'])->name('bulk_action');

        // STL record routes (with names, matching medical report style)
        Route::post('save-stl', [PatientEncounterController::class, 'saveStl'])->name('save-stl');
        Route::post('update-stl/{id}', [PatientEncounterController::class, 'updateStl'])->name('update-stl');
        Route::get('edit-stl/{id}', [PatientEncounterController::class, 'editStl'])->name('edit-stl');
        Route::get('delete-stl/{id}', [PatientEncounterController::class, 'deleteStl'])->name('delete-stl');
        Route::get('list-stl/{encounter_id}', [PatientEncounterController::class, 'listStlByEncounter'])->name('list-stl');
        Route::get('get-stl-files/{id}', [PatientEncounterController::class, 'getStlFiles'])->name('get-stl-files');
        // // encounter module routes starts here...
        // Route::get('patient_encounter_list'  , [ PatientEncounterController::class,'index']);
        // Route::post('patient_encounter_save' ,   [PatientEncounterController::class,'save']);
        // Route::get('patient_encounter_edit'   , [ PatientEncounterController::class,'edit']);
        // Route::get('patient_encounter_delete' , [ PatientEncounterController::class,'delete']);
        // Route::get('patient_encounter_details', [ PatientEncounterController::class,'details']);
        // Route::post('save_custom_patient_encounter_field',  [PatientEncounterController::class,'saveCustomField']);
        // Route::post('patient_encounter_update_status',  [PatientEncounterController::class,'updateStatus']);
        // Route::get('print_encounter_bill_detail' ,   [PatientEncounterController::class,'printEncounterBillDetail']);
        // Route::get('encounter_extra_clinical_detail_fields' ,   [PatientEncounterController::class,'encounterExtraClinicalDetailFields']);
        Route::post('save-followup-note', [PatientEncounterController::class, 'saveFollowUpNote'])->name('save-followup-note');
        Route::get('edit-followup-note/{id}', [PatientEncounterController::class, 'editFollowUpNote']);
        Route::post('update-followup-note/{id}', [PatientEncounterController::class, 'updateFollowUpNote'])->name('update-followup-note');
        Route::delete('delete-followup-note/{id}', [PatientEncounterController::class, 'deleteFollowUpNote'])->name('delete-followup-note');
        Route::post('save-orthodontic-treatment-daily-record', [PatientEncounterController::class, 'saveOrthodonticTreatmentDailyRecord'])->name('save-orthodontic-treatment-daily-record');
        Route::get('edit-orthodontic-treatment-daily-record/{id}', [PatientEncounterController::class, 'editOrthodonticTreatmentDailyRecord'])->name('edit-orthodontic-treatment-daily-record');
        Route::post('update-orthodontic-treatment-daily-record/{id}', [PatientEncounterController::class, 'updateOrthodonticTreatmentDailyRecord'])->name('update-orthodontic-treatment-daily-record');
        Route::delete('delete-orthodontic-treatment-daily-record/{id}', [PatientEncounterController::class, 'deleteOrthodonticTreatmentDailyRecord'])->name('delete-orthodontic-treatment-daily-record');
        Route::get('download-orthodontic-treatment-daily-records', [Modules\Appointment\Http\Controllers\Backend\API\PatientEncounterController::class, 'downloadOrthoDailyRecordsPDF']);
    });
    Route::resource("encounter", PatientEncounterController::class);

    Route::group(['prefix' => 'encounter', 'as' => 'encounter.'], function () {
        Route::get('edit-patient-history/{id}', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'edit'])->name('encounter.edit-patient-history');
        Route::delete('delete-patient-history/{id}', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'destroy'])->name('encounter.delete-patient-history');
    });

    // Patient History Print & PDF
    Route::get('/encounter/print-patient-history/{id}', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'printPatientHistory'])->name('patient-history.print');
    Route::get('/encounter/download-patient-history-pdf/{id}', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'downloadPatientHistoryPDF'])->name('patient-history.download-pdf');
    Route::get('/encounter/download-instructions/{encounter_id}', [PatientEncounterController::class, 'downloadInstructions'])->name('download-instructions');
    Route::get('/encounter/download-instructions/{encounter_id}/{procedure_type}', [PatientEncounterController::class, 'downloadInstructions'])->name('download-instructions-by-type');

    Route::group(['prefix' => 'encounter-template', 'as' => 'encounter-template.'], function () {
        Route::get("index_list", [EncounterTemplateController::class, 'index_list'])->name("index_list");
        Route::get("index_data", [EncounterTemplateController::class, 'index_data'])->name("index_data");
        Route::get('export', [EncounterTemplateController::class, 'export'])->name('export');
        Route::post('bulk-action', [EncounterTemplateController::class, 'bulk_action'])->name('bulk_action');
        Route::post('update-status/{id}', [EncounterTemplateController::class, 'updateStatus'])->name('update_status');
        Route::get('template-detail/{id}', [EncounterTemplateController::class, 'templateDetail'])->name('template-detail');
        Route::post('save-template-histroy', [EncounterTemplateController::class, 'saveTemplateHistroy'])->name('save-template-histroy');
        Route::get('remove-template-histroy', [EncounterTemplateController::class, 'removeTemplateHistroy']);
        Route::post('save-prescription', [EncounterTemplateController::class, 'savePrescription']);
        Route::get('edit-prescription/{id}', [EncounterTemplateController::class, 'editPrescription']);
        Route::post('update-prescription/{id}', [EncounterTemplateController::class, 'updatePrescription']);
        Route::get('delete-prescription/{id}', [EncounterTemplateController::class, 'deletePrescription']);
        Route::post('save-other-details', [EncounterTemplateController::class, 'saveOtherDetails'])->name('save-other-details');
        Route::get('template-list', [EncounterTemplateController::class, 'index_list'])->name("index_list");
        Route::get('get-template-detail/{id}', [EncounterTemplateController::class, 'getTemplateDetails']);
    });
    Route::resource("encounter-template", EncounterTemplateController::class);

    Route::group(['prefix' => 'billing-record', 'as' => 'billing-record.'], function () {
        Route::get("index_list", [BillingRecordController::class, 'index_list'])->name("index_list");
        Route::get("index_data", [BillingRecordController::class, 'index_data'])->name("index_data");
        Route::post('bulk-action', [BillingRecordController::class, 'bulk_action'])->name('bulk_action');
        Route::get('export', [BillingRecordController::class, 'export'])->name('export');
        Route::post('/update-status/{id}', [BillingRecordController::class, 'updateStatus'])->name('updateStatus');

        Route::post('/save-billing-details', [BillingRecordController::class, 'saveBillingDetails']);
        Route::get('billing-detail', [BillingRecordController::class, 'billing_detail'])->name('billing_detail');

        Route::get('edit-billing-detail', [BillingRecordController::class, 'EditBillingDetails']);

        Route::get('encounter_billing_detail', [BillingRecordController::class, 'encounter_billing_detail']);
        Route::post('save-billing-items', [BillingRecordController::class, 'saveBillingItems']);
        Route::get('billing-item-details', [BillingRecordController::class, 'billing_item_detail'])->name('billing_item_detail');
        Route::get('edit-billing-item/{id}', [BillingRecordController::class, 'editBillingItem']);
        Route::post('update-billing-item', [BillingRecordController::class, 'updateBillingItem']);
        Route::get('delete-billing-item/{id}', [BillingRecordController::class, 'deleteBillingItem']);
        Route::get('get-billing-record/{id}', [BillingRecordController::class, 'getBillingItem']);
        Route::post('calculate-discount-record', [BillingRecordController::class, 'CalculateDiscount']);
        Route::post('save-billing-detail-data', [BillingRecordController::class, 'SaveBillingData']);
        Route::post('/installments/store', [BillingRecordController::class, 'storeInstallment'])->name('installments.store');
        Route::get('/billing/installments/download/{installment_id}', [BillingRecordController::class, 'downloadInstallmentPDF'])
            ->name('download.installment.pdf');
        Route::get('/download/{id}', [BillingRecordController::class, 'downloadBillingPDF'])
            ->name('download.pdf');
    });

    Route::resource("billing-record", BillingRecordController::class);

    Route::group(['prefix' => 'problems', 'as' => 'problems.'], function () {
        Route::get("index_list", [ProblemsController::class, 'index_list'])->name("index_list");
        Route::get("index_data", [ProblemsController::class, 'index_data'])->name("index_data");
        Route::post('bulk-action', [ProblemsController::class, 'bulk_action'])->name('bulk_action');
    });
    Route::resource("problems", ProblemsController::class);
    Route::get("problem_fillter", [ProblemsController::class, 'problemFillter'])->name("problem_fillter");


    Route::group(['prefix' => 'observation', 'as' => 'observation.'], function () {
        Route::get("index_list", [ObservationController::class, 'index_list'])->name("index_list");
        Route::get("index_data", [ObservationController::class, 'index_data'])->name("index_data");
        Route::post('bulk-action', [ObservationController::class, 'bulk_action'])->name('bulk_action');
    });
    Route::resource("observation", ObservationController::class);

    // Patient History Step-wise CRUD
    Route::prefix('appointment/patient-history')->group(function () {
        // Demographic
        Route::post('demographic', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'storeDemographic'])->name('patient-history.demographic.store');
        Route::get('demographic/{id}', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'getDemographic'])->name('patient-history.demographic.get');
        // Medical History
        Route::post('medical-history', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'storeMedicalHistory'])->name('patient-history.medical-history.store');
        Route::get('medical-history/{id}', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'getMedicalHistory'])->name('patient-history.medical-history.get');
        // Dental History
        Route::post('dental-history', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'storeDentalHistory'])->name('patient-history.dental-history.store');
        Route::get('dental-history/{id}', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'getDentalHistory'])->name('patient-history.dental-history.get');
        // Dental Chart (Jaw Treatments)
        Route::post('dental-chart', [
            \Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class,
            'storeDentalChart'
        ])->name('patient-history.dental-chart.store');
        Route::get('dental-chart/{patient_history_id}', [
            \Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class,
            'getDentalChart'
        ])->name('patient-history.dental-chart.get');
        // Chief Complaint
        Route::post('chief-complaint', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'storeChiefComplaint'])->name('patient-history.chief-complaint.store');
        Route::get('chief-complaint/{id}', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'getChiefComplaint'])->name('patient-history.chief-complaint.get');
        // Clinical Examination
        Route::post('clinical-examination', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'storeClinicalExamination'])->name('patient-history.clinical-examination.store');
        Route::get('clinical-examination/{id}', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'getClinicalExamination'])->name('patient-history.clinical-examination.get');
        // Radiographic Examination
        Route::post('radiographic-examination', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'storeRadiographicExamination'])->name('patient-history.radiographic-examination.store');
        Route::get('radiographic-examination/{id}', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'getRadiographicExamination'])->name('patient-history.radiographic-examination.get');
        // Diagnosis and Plan
        Route::post('diagnosis-plan', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'storeDiagnosisAndPlan'])->name('patient-history.diagnosis-plan.store');
        Route::get('diagnosis-plan/{id}', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'getDiagnosisAndPlan'])->name('patient-history.diagnosis-plan.get');
        // Informed Consent
        Route::post('informed-consent', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'storeInformedConsent'])->name('patient-history.informed-consent.store');
        Route::get('informed-consent/{id}', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'getInformedConsent'])->name('patient-history.informed-consent.get');
        Route::post('create', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'create'])->name('patient-history.create');
        Route::get('find-by-encounter/{encounter_id}', [\Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class, 'findByEncounter']);
        Route::post('mark-complete/{id}', [
            \Modules\Appointment\Http\Controllers\Backend\PatientHistoryController::class,
            'markComplete'
        ])->name('patient-history.mark-complete');
    });

    // STL record routes

});
