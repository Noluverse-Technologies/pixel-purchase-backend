<?php

use Illuminate\Http\Request;
use Modules\Pixels\Http\Controllers\PixelsController;

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
    Route::group(['prefix' => 'pixel'], function () {
        Route::group(['prefix' => 'package'], function () {
            Route::get('/view', [PixelsController::class, 'getPixelPackages']);
            Route::middleware(['can:create_license_pixels'])->group(function () {
                Route::post('/create', [PixelsController::class, 'createPixelPackage']);
                Route::post('/edit', [PixelsController::class, 'updatePixelPackage']);
            });
        });
    });
});
