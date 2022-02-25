<?php

use Illuminate\Http\Request;
use Modules\License\Http\Controllers\LicenseController;

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

    //pixel routes
    Route::group(['prefix' => 'license'], function () {
        Route::group(['prefix' => 'package'], function () {
            Route::get('/view', [LicenseController::class, 'getLicensePackages']);
            Route::middleware(['can:create_license_pixels'])->group(function () {
                Route::post('/create', [LicenseController::class, 'createLicensePackage']);
                Route::post('/edit', [LicenseController::class, 'updatePixelPackage']);
            });
        });
    });
});
