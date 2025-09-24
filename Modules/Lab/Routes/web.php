<?php

use Illuminate\Support\Facades\Route;
use Modules\Lab\Http\Controllers\LabController;
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
//     Route::resource('lab', LabController::class)->names('lab');
// });


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
     *  Backend Lab Routes
     *
     * ---------------------------------------------------------------------
     */

    Route::group(['prefix' => 'lab', 'as' => 'lab.'],function () {
        Route::post('lab-bulk-action', [LabController::class, 'bulk_action'])->name('bulk_action');
        Route::get('lab-index-data', [LabController::class, 'index_data'])->name('index_data');
        Route::get('lab-index', [LabController::class, 'index'])->name('index');
        Route::post('lab-action', [LabController::class, 'action'])->name('action');
        Route::post('update-status/{id}', [LabController::class, 'update_status'])->name('update_status');
        Route::post('lab/{id}', [LabController::class, 'destroy'])->name('destroy');
        Route::get('lab/{id}/remove-media/{media_id}', [LabController::class, 'removeMedia'])->name('remove-media');
        Route::get('lab/{id}/print', [LabController::class, 'printLab'])->name('print');
        Route::get('lab/{id}/download-pdf', [LabController::class, 'downloadLabPDF'])->name('download-pdf');
        Route::get('lab/{id}/download-file/{media_id}', [LabController::class, 'downloadFile'])->name('download-file');

        Route::get('export', [LabController::class, 'export'])->name('export');
    });
    
    // Resource routes for Lab CRUD operations
    Route::resource('lab', LabController::class);
});                                                                                                                                                                                             