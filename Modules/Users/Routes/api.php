<?php


use Modules\Users\Http\Controllers\AuthController;
use Modules\Users\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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



/**
 * 
 */
Route::group(['middleware' => 'auth:api'], function () {

    Route::get('logout', [AuthController::class, 'logout']);

    //user roles routes
    Route::group(['prefix' => 'role'], function () {
        Route::middleware(['can:create_user_roles'])->group(function () {
            Route::post('/create', 'UsersController@createUserRoles');
            Route::get('/view', 'UsersController@getUserRoles');
            Route::put('/edit', 'UsersController@updateUserRoles');
            // Route::delete('/delete', 'UsersController@deleteUserRoles');
        });
    });
});
