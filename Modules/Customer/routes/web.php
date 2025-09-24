<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Backend\CustomersController;
use Modules\Customer\Http\Controllers\Backend\VitalsController;
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
Route::group(['prefix' => 'app', 'as' => 'backend.', 'middleware' => ['auth','auth_check']], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     */

    /*
     *
     *  Backend Customers Routes
     *
     * ---------------------------------------------------------------------
     */

    Route::group(['prefix' => 'customers', 'as' => 'customers.'], function () {
        Route::get('index_list', [CustomersController::class, 'index_list'])->name('index_list');
        Route::get('index_data', [CustomersController::class, 'index_data'])->name('index_data');
        Route::get('trashed', [CustomersController::class, 'trashed'])->name('trashed');
        Route::get('trashed/{id}', [CustomersController::class, 'restore'])->name('restore');
        Route::post('bulk-action', [CustomersController::class, 'bulk_action'])->name('bulk_action');
        Route::post('change-password', [CustomersController::class, 'change_password'])->name('change_password');
        Route::post('update-status/{id}', [CustomersController::class, 'update_status'])->name('update_status');
        Route::post('block-customer/{id}', [CustomersController::class, 'block_customer'])->name('block-customer');
        Route::post('verify-customer/{id}', [CustomersController::class, 'verify_customer'])->name('verify-customer');
        Route::get('export', [CustomersController::class, 'export'])->name('export');
        Route::get('/backend/customers/patient_detail/{id}', [CustomersController::class, 'patient_detail'])->name('patient_detail');
        Route::delete('otherPatient/{id}', [CustomersController::class, 'delete'])->name('otherPatient.delete');
        Route::put('/other-patient/update/{id}', [CustomersController::class, 'updateOtherPatient'])->name('otherPatient.update');
        Route::post('other-patient', [CustomersController::class, 'otherpatient'])->name('other_patient');

        Route::get('customer-details/{id}', [CustomersController::class, 'customerDetails'])->name('customer_details');
        Route::post('import-users', [CustomersController::class, 'importUsers'])->name('import-users');
        Route::get('download-sample/{type}', [CustomersController::class, 'downloadSample'])->name('download-sample');
        Route::get('patient-history', function () {
            return view('customer::backend.customers.patient_history');
        })->name('patient_history');
        Route::get('patient-history/{id}', [CustomersController::class, 'patientHistory'])->name('patient_history');
    });
    Route::resource('customers', CustomersController::class);
});
//     Route::get("index_list", [VitalsController::class, 'index_list'])->name("index_list");
//     Route::get("index_data", [VitalsController::class, 'index_data'])->name("index_data");
//     Route::get('export', [VitalsController::class, 'export'])->name('export');

//   Route::resource("vitals", VitalsController::class);
