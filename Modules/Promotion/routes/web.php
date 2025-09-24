<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

use Modules\Promotion\Http\Controllers\Backend\PromotionsController;
use Modules\Promotion\Http\Controllers\Backend\CouponController;

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
     *  Backend Promotions Routes
     *
     * ---------------------------------------------------------------------
     */

    Route::group(['prefix' => 'promotions', 'as' => 'promotions.'],function () {
      Route::get("index_list", [PromotionsController::class, 'index_list'])->name("index_list");
      Route::get("index_data", [PromotionsController::class, 'index_data'])->name("index_data");
      Route::get('export', [PromotionsController::class, 'export'])->name('export');
      Route::post('update-status/{id}', [PromotionsController::class, 'update_status'])->name('update_status');
      Route::put('coupon-validate',[PromotionsController::class, 'couponvalidate'])->name('coupon-validate');
      Route::get("coupon-data/{id}", [PromotionsController::class, 'coupon_data'])->name("coupon_data");

    });
    Route::resource("promotions", PromotionsController::class);
    Route::get('coupons.export/{id}', [PromotionsController::class, 'couponExport'])->name('coupons.export');
    Route::get('coupons-view/{id}',[PromotionsController::class, 'couponsview'])->name('coupons-view');


});

