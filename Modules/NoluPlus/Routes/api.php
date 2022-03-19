<?php

use Illuminate\Http\Request;
use Modules\NoluPlus\Http\Controllers\NoluPlusController;

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

    Route::group(['prefix' => 'noluplus'], function () {
        Route::group(['prefix' => 'package'], function () {

            Route::get('/view', [NoluPlusController::class, 'getNoluPlusPackages']);
            //only admin can manage user subscription
            Route::middleware(['can:create_license_pixels'])->group(function () {
                Route::post('/create', [NoluPlusController::class, 'createPackage']);
                Route::delete('/delete', [NoluPlusController::class, 'deleteNoluPlusPackageById']);
            });
        });
    });
});
