<?php

use Illuminate\Http\Request;

use Modules\Subscriptions\Http\Controllers\SubscriptionsController;

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

//*subscription routes
Route::group(['middleware' => 'auth:api'], function () {


    Route::group(['prefix' => 'subscribe'], function () {
        //subscribe parent level routes
        Route::get('/view_user_subscription/{id}', [SubscriptionsController::class, 'getSubscriptionByUser']);

        Route::get('/view_all_subscription_by_user/{id}', [SubscriptionsController::class, 'getAllUserSubscriptionById']);

        Route::get('/view_all_subscription', [SubscriptionsController::class, 'getAllSubscriptions']);
        Route::get('/view_all_nolu_plus_subscription', [SubscriptionsController::class, 'getNoluPlusSubscriptionByUser']);

        Route::post('/create', [SubscriptionsController::class, 'createSubscription']);

        Route::post('/create_nolu_plus', [SubscriptionsController::class, 'createNoluPlusSubscription']);


        Route::post('/edit', [SubscriptionsController::class, 'updateSubscription']);

        //subscribed type routes
        Route::group(['prefix' => 'type'], function () {

            //only admin can manage user subscription
            Route::middleware(['can:can_manage_user_subscription'])->group(function () {
                Route::post('/create', [SubscriptionsController::class, 'createSubscriptionType']);
                Route::post('/edit', [SubscriptionsController::class, 'updateSubscriptionType']);
                Route::get('/view', [SubscriptionsController::class, 'getAllSubscriptionType']);
            });
        });
    });
});
