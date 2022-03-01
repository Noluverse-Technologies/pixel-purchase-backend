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

//*any logged in user will be able to buy pixels and licenses
Route::group(['middleware' => 'auth:api'], function () {

    //pixel routes
    Route::group(['prefix' => 'subscribe'], function () {
        //subscribe parent level routes
        Route::get('/view_user_subscription/{id}', [SubscriptionsController::class, 'getSubscriptionByUser']);
        Route::get('/view_all_subscription', [SubscriptionsController::class, 'getAllSubscriptions']);
        Route::post('/create', [SubscriptionsController::class, 'createSubscription']);
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
