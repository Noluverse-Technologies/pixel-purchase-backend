<?php

use Illuminate\Http\Request;
use Modules\Users\Http\Controllers\AuthController;

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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function () {


    /**
     * Sample gate implementation 
     */
    Route::middleware(['can:isAdmin'])->group(function () {
        Route::get('/admintest', 'AuthController@adminTest');
    });
});
