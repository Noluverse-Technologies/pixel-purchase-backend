<?php

use Illuminate\Http\Request;
use Modules\Overview\Http\Controllers\OverviewController;

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

    Route::group(['prefix' => 'events'], function () {
        Route::get('/view', [OverviewController::class, 'getEvents']);

        Route::middleware(['can:create_license_pixels'])->group(function () {
            Route::post('/create', [OverviewController::class, 'createEvents']);
            Route::delete('/delete', [OverviewController::class, 'deleteEvents']);
        });
    });
    //pixel routes
    Route::group(['prefix' => 'overview'], function () {
        Route::get('/data', [OverviewController::class, 'OverviewCalculations']);
    });
});
