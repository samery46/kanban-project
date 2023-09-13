<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('home', [TaskController::class, 'home']);
// Route::get('tasks', [TaskController::class, 'index']);
// Route::get('users', [UserController::class, 'index']);
// Route::get('roles', [RoleController::class, 'index']);

Route::resource('/tasks', TaskController::class);
// Route::resource('/roles', RoleController::class);->middleware('auth:sanctum');
Route::resource('/roles', RoleController::class);
// Route::resource('/roles', RoleController::class);->middleware('auth:sanctum');
Route::resource('/users', UserController::class);
// Route::resource('/users', UserController::class);->middleware('auth:sanctum');



Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('otp', 'otp');

    Route::middleware('auth:sanctum')->group(function () {

        Route::prefix('profile')->group(function () {
            Route::get('/', 'profile');
            Route::post('/', 'update');
        });

        Route::post('logout', 'logout');
    });
});
