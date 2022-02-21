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

Route::post('login', [AuthController::class, 'login'])->name('login');;
Route::post('register', [AuthController::class, 'register'])->name('register');




Route::group(['middleware' => 'auth:api'], function () {

    Route::get('logout', [AuthController::class, 'logout']);


    //user roles routes
    Route::group(['prefix' => 'role'], function () {
        Route::middleware(['can:create_user_roles'])->group(function () {
            Route::post('/create', [UsersController::class, 'createUserRoles']);
            Route::post('/view', [UsersController::class, 'getUserRoles']);
            Route::post('/edit', [UsersController::class, 'updateUserRoles']);
        });
    });

    //user routes
    Route::group(['prefix' => 'user'], function () {
        Route::post('/edit', [UsersController::class, 'updateCurrentUser']);
    });
});
