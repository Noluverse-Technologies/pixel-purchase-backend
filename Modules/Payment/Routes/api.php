<?php

use Illuminate\Http\Request;
use Modules\Payment\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('/paywithdrawalfee', [PaymentController::class, 'payWithdrawalFee']);
    //subscribed type routes
    Route::group(['prefix' => 'reward'], function () {
        Route::post('/calculate', [PaymentController::class, 'calculateReward']);

        Route::post('/claimall', [PaymentController::class, 'claimAllReward']);
    });

    //subscribed type routes
    Route::group(['prefix' => 'transactions'], function () {

        //only admin can manage user subscription
        Route::middleware(['can:can_view_transactions'])->group(function () {
            Route::get('/view', [PaymentController::class, 'getAllTransactionByUser']);

            Route::get('/view_by_month', [PaymentController::class, 'getUserTransactionsByMonth']);
        });
    });
});
