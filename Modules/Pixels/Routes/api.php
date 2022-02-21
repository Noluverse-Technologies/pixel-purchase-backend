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

    Route::get('logout', [AuthController::class, 'logout']);

    //user roles routes
    Route::group(['prefix' => 'pixel'], function () {
        Route::group(['prefix' => 'package'], function () {
            Route::middleware(['can:create_pixels'])->group(function () {
                Route::post('/create', [PixelsController::class, 'createPixelPackage']);
                Route::post('/edit', [PixelsController::class, 'updatePixelPackage']);
            });
        });
    });
});
