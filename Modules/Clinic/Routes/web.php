<?php

use Illuminate\Support\Facades\Route;
use Modules\Clinic\Models\Clinic;
use Modules\Clinic\Http\Controllers\ClinicesController;
use Modules\Clinic\Http\Controllers\ClinicsServiceController;
use Modules\Clinic\Http\Controllers\ClinicsCategoryController;
use Modules\Clinic\Http\Controllers\DoctorController;
use Modules\Clinic\Http\Controllers\ClinicAppointmentController;
use Modules\Clinic\Http\Controllers\DoctorSessionController;
use Modules\Clinic\Http\Controllers\ClinicSessionController;
use Modules\Clinic\Http\Controllers\ReceptionistController;
use Modules\Clinic\Http\Controllers\SystemServiceController;

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

// Route::group([], function () {
//     Route::resource('clinic', ClinicController::class)->names('clinic');
// });
Route::group(['prefix' => 'app', 'as' => 'backend.', 'middleware' => ['auth', 'auth_check']], function () {
    Route::group(['prefix' => 'clinics', 'as' => 'clinics.'], function () {
        Route::get('index_list', [ClinicesController::class, 'index_list'])->name('index_list');
        Route::get('index_data', [ClinicesController::class, 'index_data'])->name('index_data');
        Route::post('bulk-action', [ClinicesController::class, 'bulk_action'])->name('bulk_action');
        Route::post('update-status/{id}', [ClinicesController::class, 'update_status'])->name('update_status');
        Route::get('gallery-images/{id}', [ClinicesController::class, 'getGalleryImages']);
        Route::post('gallery-images/{id}', [ClinicesController::class, 'uploadGalleryImages']);
        Route::get('export', [ClinicesController::class, 'export'])->name('export');
        Route::get('clinic-details/{id}', [ClinicesController::class, 'clinicDetails'])->name('clinic_details');
        Route::get('clinic_list', [ClinicesController::class, 'clinicList'])->name('clinic_list');
    });
    Route::resource('clinics', ClinicesController::class);

    Route::group(['prefix' => 'clinic-session', 'as' => 'clinic-session.'], function () {
        Route::get('index_list', [ClinicSessionController::class, 'index_list'])->name('index_list');
        Route::get('index_data', [ClinicSessionController::class, 'index_data'])->name('index_data');
    });

    Route::resource('clinic-session', ClinicSessionController::class);



    Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
        Route::get('index_list', [ClinicsCategoryController::class, 'index_list'])->name('index_list');
        Route::get('index_data', [ClinicsCategoryController::class, 'index_data'])->name('index_data');
        Route::post('bulk-action', [ClinicsCategoryController::class, 'bulk_action'])->name('bulk_action');
        Route::post('update-status/{id}', [ClinicsCategoryController::class, 'update_status'])->name('update_status');
        Route::post('update_featured/{id}', [ClinicsCategoryController::class, 'update_featured'])->name('update_featured');
        Route::post('update-clinic-category/{id}', [ClinicsCategoryController::class, 'update'])->name('update_clinic_category');
        Route::get('export', [ClinicsCategoryController::class, 'export'])->name('export');
    });
    Route::get('category-table-view', [ClinicsCategoryController::class, 'datatable_view'])->name('category.datatable_view');

    Route::get('clinic-sub-categories', [ClinicsCategoryController::class, 'index_nested'])->name('category.index_nested');
    Route::get('clinic-sub-categories/index_nested_data', [ClinicsCategoryController::class, 'index_nested_data'])->name('category.index_nested_data');
    Route::resource('category', ClinicsCategoryController::class);



    Route::group(['prefix' => 'services', 'as' => 'services.'], function () {
        Route::get('index_list', [ClinicsServiceController::class, 'index_list'])->name('index_list');
        Route::get('index_data', [ClinicsServiceController::class, 'index_data'])->name('index_data');
        Route::get('trashed', [ClinicsServiceController::class, 'trashed'])->name('trashed');
        Route::patch('trashed/{id}', [ClinicsServiceController::class, 'restore'])->name('restore');
        Route::get('discount-price', [ClinicsServiceController::class, 'discountPrice'])->name('service_price');
        Route::get('index_list_data', [ClinicsServiceController::class, 'index_list_data'])->name('index_list_data');
        Route::post('bulk-action', [ClinicsServiceController::class, 'bulk_action'])->name('bulk_action');
        Route::post('update-status/{id}', [ClinicsServiceController::class, 'update_status'])->name('update_status');
        Route::get('export', [ClinicsServiceController::class, 'export'])->name('export');
        Route::get('service-details', [ClinicsServiceController::class, 'ServiceDetails']);
        Route::post('assign-doctor-store/{id}', [ClinicsServiceController::class, 'assign_doctor_update'])->name('assign_doctor_update');
        Route::get('service-price', [ClinicsServiceController::class, 'service_price'])->name('service_price');
    });
    Route::resource('services', ClinicsServiceController::class);


    Route::group(['prefix' => 'system-service', 'as' => 'system-service.'], function () {
        Route::get('index_list', [SystemServiceController::class, 'index_list'])->name('index_list');
        Route::get('index_data', [SystemServiceController::class, 'index_data'])->name('index_data');
        Route::get('trashed', [SystemServiceController::class, 'trashed'])->name('trashed');
        Route::patch('trashed/{id}', [SystemServiceController::class, 'restore'])->name('restore');
        Route::post('bulk-action', [SystemServiceController::class, 'bulk_action'])->name('bulk_action');
        Route::post('update-status/{id}', [SystemServiceController::class, 'update_status'])->name('update_status');
        Route::post('update-featured/{id}', [SystemServiceController::class, 'update_featured'])->name('update_featured');
        Route::get('export', [SystemServiceController::class, 'export'])->name('export');
    });
    Route::resource('system-service', SystemServiceController::class);






    Route::group(['prefix' => 'receptionist', 'as' => 'receptionist.'], function () {
        Route::get('index_list', [ReceptionistController::class, 'index_list'])->name('index_list');
        Route::get('index_data', [ReceptionistController::class, 'index_data'])->name('index_data');
        Route::post('bulk-action', [ReceptionistController::class, 'bulk_action'])->name('bulk_action');
        Route::post('update-status/{id}', [ReceptionistController::class, 'update_status'])->name('update_status');
        Route::post('change-password', [ReceptionistController::class, 'change_password'])->name('change_password');
        Route::post('verify-receptionist/{id}', [ReceptionistController::class, 'verify_receptionist'])->name('verify-receptionist');
        Route::get('export', [ReceptionistController::class, 'export'])->name('export');
    });

    Route::resource('receptionist', ReceptionistController::class);





    // doctor Routes
    Route::group(['prefix' => 'doctor', 'as' => 'doctor.'], function () {
        Route::get('index_list', [DoctorController::class, 'index_list'])->name('index_list');
        Route::get('commision_list', [DoctorController::class, 'commision_list'])->name('commision_list');
        Route::get('export', [DoctorController::class, 'export'])->name('export');
        Route::get('employee_list', [DoctorController::class, 'employee_list'])->name('employee_list');
        Route::get('get-available-slot', [DoctorController::class, 'availableSlot'])->name('get_available_slot');
        Route::post('change-password', [DoctorController::class, 'change_password'])->name('change_password');
        Route::post('update-status/{id}', [DoctorController::class, 'update_status'])->name('update_status');
        Route::post('block-employee/{id}', [DoctorController::class, 'block_employee'])->name('block-employee');
        Route::get('verify-doctor/{id}', [DoctorController::class, 'verify_doctor'])->name('verify-doctor');
        Route::get('review_data', [DoctorController::class, 'review_data'])->name('review_data');
        Route::delete('destroy_review/{id}', [DoctorController::class, 'destroy_review'])->name('destroy_review');
        Route::get('index_data', [DoctorController::class, 'index_data'])->name('index_data');
        Route::get('trashed', [DoctorController::class, 'trashed'])->name('trashed');
        Route::get('trashed/{id}', [DoctorController::class, 'restore'])->name('restore');
        Route::post('bulk-action', [DoctorController::class, 'bulk_action'])->name('bulk_action');
        // Route::post('bulk-action-review', [DoctorController::class, 'bulk_action_review'])->name('bulk_action_review');
        Route::get('/type/{type}', [DoctorController::class, 'employees_type_data'])->name('employee_type');
        Route::post('/send-push-notification', [DoctorController::class, 'send_push_notification'])->name('send-push-notification');
        Route::get('/assign-doctor-list', [ClinicsServiceController::class, 'assign_doctor_list'])->name('assign_doctor_list');
        // Route::get('view', [DoctorController::class, 'view'])->name('view');
        Route::get('doctor-details/{id}', [DoctorController::class, 'doctorDeatails'])->name('doctorDeatails');
        Route::get('review_data', [DoctorController::class, 'review_data'])->name('review_data');
        Route::get('review/{id}', [DoctorController::class, 'show_review'])->name('review_show');
        Route::post('bulk-action-review', [DoctorController::class, 'bulk_action_review'])->name('bulk_action_review');
        Route::delete('destroy_review/{id}', [DoctorController::class, 'destroy_review'])->name('destroy_review');
        Route::get('service_list', [DoctorController::class, 'service_list'])->name('service_list');
        Route::get('user-list', [DoctorController::class, 'user_list'])->name('user_list');
        Route::get('doctor-list', [DoctorController::class, 'doctor_list'])->name('doctor_list');
        Route::get('appointment-services', [DoctorController::class, 'appointment_services'])->name('appointment_services');
        Route::get('patients-from-appointments', [DoctorController::class, 'getPatientsFromAppointments'])->name('patients_from_appointments');
        Route::get('doctors-from-appointments', [DoctorController::class, 'getDoctorsFromAppointments'])->name('doctors_from_appointments');
        Route::get('services-from-appointments', [DoctorController::class, 'getServicesFromAppointments'])->name('services_from_appointments');
        Route::post('store-review', [DoctorController::class, 'storeReview'])->name('storeReview');
        Route::post('add-patient-feedback', [DoctorController::class, 'addPatientFeedback'])->name('add_patient_feedback');
    });
    Route::get('doctors-review', [DoctorController::class, 'review'])->name('doctors.review');
    Route::get('employees-review', [DoctorController::class, 'review'])->name('employees.review');
    Route::get('all-employees', [DoctorController::class, 'index'])->name('employees.all');
    Route::resource('doctor', DoctorController::class);


    Route::group(['prefix' => 'doctor-session', 'as' => 'doctor-session.'], function () {
        Route::get('index_list', [DoctorSessionController::class, 'index_list'])->name('index_list');
        Route::get('index_data', [DoctorSessionController::class, 'index_data'])->name('index_data');
        Route::post('bulk-action', [DoctorSessionController::class, 'bulk_action'])->name('bulk_action');
        Route::post('update-status/{id}', [DoctorSessionController::class, 'update_status'])->name('update_status');
        Route::get('export', [DoctorSessionController::class, 'export'])->name('export');
        Route::get('day-list', [DoctorSessionController::class, 'session_list'])->name('session_list');
        Route::get('edit-session-data', [DoctorSessionController::class, 'EditSessionData']);
        Route::get('edit-doctor-mapping', [DoctorSessionController::class, 'EditDoctorMapping']);
    });

    Route::resource('doctor-session', DoctorSessionController::class);
});
