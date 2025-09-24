<?php

use Illuminate\Support\Facades\Route;
use Modules\Frontend\Http\Controllers\FrontendController;
use Modules\Frontend\Http\Controllers\CategoryController;
use Modules\Frontend\Http\Controllers\ServiceController;
use Modules\Frontend\Http\Controllers\ClinicController;
use Modules\Frontend\Http\Controllers\DoctorController;
use Modules\Frontend\Http\Controllers\BlogController;
use Modules\Frontend\Http\Controllers\AppointmentController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Backend\NotificationsController;
use Modules\Frontend\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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

Route::get('/login', [UserController::class, 'login'])->name('login-page');
Route::get('/register', [UserController::class, 'registration'])->name('register-page');
Route::get('/forgot-password', [UserController::class, 'forgotpassword'])->name('forgot-password');
Route::post('forgot-password', [UserController::class, 'store'])->name('password.emailuser');
Route::get('reset-password/{token}', [UserController::class, 'create'])->name('password.reset');
Route::post('reset-password', [UserController::class, 'storepassword'])->name('password.update');
Route::post('/forgot-password-link', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::controller(UserController::class)->group(function () {
    Route::post('user-login', 'loginstore')->name('user-login');
    // Login with Google
    Route::get('/auth/google', 'redirectToGoogle')->name('auth.google');
    Route::get('/auth/google/callback', 'handleGoogleCallback')->name('auth.google.callback');
});
Route::get('language/{language}', [LanguageController::class, 'switch'])->name('language.switch');

Route::middleware(['check.header.menu'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'categoriesList'])->name('categories');
    Route::get('/services', [ServiceController::class, 'servicesList'])->name('services');
    Route::get('/clinics', [ClinicController::class, 'clinicsList'])->name('clinics');
    Route::get('/doctors', [DoctorController::class, 'doctorsList'])->name('doctors');


    Route::get('/service-details/{id}', [ServiceController::class, 'serviceDetails'])->name('service-details');
    Route::get('/clinic-details/{id}', [ClinicController::class, 'clinicDetails'])->name('clinic-details');
    Route::get('/doctor-details/{id}', [DoctorController::class, 'doctorDetails'])->name('doctor-details');
});

Route::get('/getClinicsByService', [ClinicController::class, 'getClinicsByService'])->name('getClinicsByService');
Route::get('/blogs', [BlogController::class, 'blogsList'])->name('blogs');
Route::get('/reviews', [DoctorController::class, 'reviewsList'])->name('reviews');
Route::post('/cancel-appointment/{id}', [AppointmentController::class, 'cancelAppointment'])->name('cancel-appointment');
Route::get('/get-services-by-clinic', [ClinicController::class, 'getServicesByClinic'])->name('getServicesByClinic');


Route::get('/search', [FrontendController::class, 'searchList'])->name('search');
Route::get('/get-search', [FrontendController::class, 'getSearch'])->name('getSearchData');
Route::get('/faq', [FrontendController::class, 'faqList'])->name('faq');
Route::get('/about-us', [FrontendController::class, 'aboutUs'])->name('about-us');
Route::get('/contact-us', [FrontendController::class, 'contactUs'])->name('contact-us');


Route::get('/blog-details/{id}', [BlogController::class, 'blogDetails'])->name('blog-details');

Route::group(['middleware' => ['auth', 'user_check']], function () {
    Route::get('/account-setting', [UserController::class, 'accountSetting'])->name('account-setting');
    Route::get('/edit-profile', [UserController::class, 'editProfile'])->name('edit-profile');
    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('update-profile');
    Route::post('/update-profile-image', [UserController::class, 'updateProfileImage'])->name('update-profile-image');
    Route::get('/appointment-list', [AppointmentController::class, 'appointmentList'])->name('appointment-list')->middleware('check.header.menu');
    Route::get('/wallet-history', [UserController::class, 'walletHistory'])->name('wallet-history');
    Route::get('/wallet-history-index-data', [UserController::class, 'walletHistoryIndexData'])->name('wallet-history.index_data');

    Route::post('/account/password/update', [UserController::class, 'updatePassword'])->name('account.password.update');
    Route::delete('/account/delete', [UserController::class, 'deleteAccount'])->name('account.delete');
    Route::post('user-logout', [UserController::class, 'destroy'])->name('user-logout');

    Route::get('/encounter-list', [AppointmentController::class, 'encounterList'])->name('encounter-list');
    Route::post('/save-appointment', [AppointmentController::class, 'saveAppointment'])->name('saveAppointment');
    Route::get('/user-notifications', [UserController::class, 'userNotifications'])->name('user-notifications');
    Route::get('/user-notifications-index-data', [UserController::class, 'userNotifications_indexData'])->name('user-notifications.index_data');
    Route::get('booking/{id}', [ServiceController::class, 'booking'])->name('booking');
    Route::post('/pay-now', [AppointmentController::class, 'payNow'])->name('pay-now');
    Route::get('/appointment-details/{id}', [AppointmentController::class, 'appointmentDetails'])->name('appointment-details');
    Route::get('/other-patients-list', [AppointmentController::class, 'otherpatientlist'])->name('other-patients.list');
    Route::post('other-patients', [AppointmentController::class, 'otherpatient'])->name('other-patients.store');
    Route::get('/manage-profile', [AppointmentController::class, 'manageProfile'])->name('manage-profile');
    Route::get('/manage-profile-data', [AppointmentController::class, 'manageProfile_index_data'])->name('manage-profile-data');
    Route::get('/other-patients/{id}/edit', [AppointmentController::class, 'editOtherPatient'])->name('other-patients.edit');
    Route::post('/other-patients/{id}', [AppointmentController::class, 'updateOtherPatient'])->name('other-patients.update');
    Route::delete('/other-patients/{id}', [AppointmentController::class, 'destroyOtherPatient'])->name('other-patients.destroy');

    // Download routes for appointment sections
    Route::get('/download-problems/{appointment_id}', [AppointmentController::class, 'downloadProblems'])->name('frontend.download-problems');
    Route::get('/download-observations/{appointment_id}', [AppointmentController::class, 'downloadObservations'])->name('frontend.download-observations');
    Route::get('/download-notes/{appointment_id}', [AppointmentController::class, 'downloadNotes'])->name('frontend.download-notes');
    Route::get('/download-bodycharts/{appointment_id}', [AppointmentController::class, 'downloadBodyCharts'])->name('frontend.download-bodycharts');
    Route::get('/download-medical-reports/{appointment_id}', [AppointmentController::class, 'downloadMedicalReports'])->name('frontend.download-medical-reports');
    Route::get('/download-prescriptions/{appointment_id}', [AppointmentController::class, 'downloadPrescriptions'])->name('frontend.download-prescriptions');
    Route::get('/download-soap/{appointment_id}', [AppointmentController::class, 'downloadSoap'])->name('frontend.download-soap');
    Route::get('/download-stl-records/{appointment_id}', [AppointmentController::class, 'downloadStlRecords'])->name('frontend.download-stl-records');
    Route::get('/download-stl-files/{stl_id}', [AppointmentController::class, 'downloadStlFiles'])->name('frontend.download-stl-files');
    Route::get('/download-patient-history-pdf/{id}', [AppointmentController::class, 'downloadPatientHistoryPDF'])->name('frontend.download-patient-history-pdf');
    Route::get('/download-medical-report/{report_id}', [AppointmentController::class, 'downloadMedicalReport'])->name('frontend.download-medical-report');
    Route::get('/download-followup-notes/{appointment_id}', [AppointmentController::class, 'downloadFollowupNotes'])->name('frontend.download-followup-notes');
    Route::get('/download-patient-history/{appointment_id}', [AppointmentController::class, 'downloadPatientHistory'])->name('frontend.download-patient-history');
    Route::get('/download-orthodontic-records/{appointment_id}', [AppointmentController::class, 'downloadOrthodonticRecords'])->name('frontend.download-orthodontic-records');
});


Route::get('category_index_data', [CategoryController::class, 'index_data'])->name('category.index_data');
Route::get('service_index_data', [ServiceController::class, 'index_data'])->name('service.index_data');
Route::get('clinic_index_data', [ClinicController::class, 'index_data'])->name('clinic.index_data');
Route::get('doctor_index_data', [DoctorController::class, 'index_data'])->name('doctor.index_data');
Route::get('blog_index_data', [BlogController::class, 'index_data'])->name('blog.index_data');
Route::get('appointment_index_data', [AppointmentController::class, 'index_data'])->name('appointment.index_data');
Route::get('encounter_index_data', [AppointmentController::class, 'encounter_index_data'])->name('encounter.index_data');

Route::post('/get-payment-data', [AppointmentController::class, 'getPaymentData'])->name('payment.data');
Route::post('/slot-time-list', [AppointmentController::class, 'slot_time_list'])->name('slot_time_list');
// Route::post('/save-appointment', [AppointmentController::class, 'saveAppointment'])->name('saveAppointment');
Route::get('/payment/success', [AppointmentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('notification-list', [NotificationsController::class, 'notificationList'])->name('notification.list');
Route::get('notification-counts', [NotificationsController::class, 'notificationCounts'])->name('notification.counts');
Route::post('/check-wallet-balance', [AppointmentController::class, 'checkWalletBalance'])->name('check.wallet.balance');
Route::post('/random-slot', [AppointmentController::class, 'randomSlot'])->name('random_slot');
Route::get('/check-booking-status/{serviceId}/{doctorId}/{clinicId}', [DoctorController::class, 'checkBookingStatus'])->name('check.booking.status');
Route::get('download_invoice', [AppointmentController::class, 'downloadPDf'])->name('download_invoice');
Route::get('/billing/installments/download/{installment_id}', [AppointmentController::class, 'downloadInstallmentPDF'])
    ->name('download.installment.pdf');
