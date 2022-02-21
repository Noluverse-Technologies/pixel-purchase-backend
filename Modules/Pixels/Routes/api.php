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
                Route::post('/create', 'PixelsController@createPixelPackage');
                // Route::get('/view', 'UsersController@getUserRoles');
                // Route::put('/edit', 'UsersController@updateUserRoles');
                // Route::delete('/delete', 'UsersController@deleteUserRoles');
            });
        });
    });

    //user routes
    Route::group(['prefix' => 'user'], function () {
        Route::post('/edit', 'UsersController@updateCurrentUser');
    });
});
