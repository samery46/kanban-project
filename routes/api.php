<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskFileController;
use App\Http\Controllers\Api\UserController;

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

Route::get('home', [TaskController::class, 'home'])->middleware('auth:sanctum');
Route::resource('/tasks', TaskController::class)->middleware('auth:sanctum');
Route::resource('/roles', RoleController::class)->middleware('auth:sanctum');
Route::resource('/users', UserController::class)->middleware('auth:sanctum');
Route::resource('/users', UserController::class)->middleware('auth:sanctum');
// Route::resource('/files', TaskFileController::class)->middleware('auth:sanctum');

Route::prefix('tasks')
    ->name('tasks.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::controller(TaskController::class)->group(function () {
            // Route::get('/', 'index')->name('index');
            // Route::get('create/{status?}', 'create')->name('create');
            // Route::post('/', 'store')->name('store');  // Ditambahkan        
            // Route::get('/{id}/edit', 'edit')->name('edit');
            // Route::put('/{id}', 'update')->name('update');
            // Route::get('/{id}/delete', 'delete')->name('delete');
            // Route::delete('/{id}', 'destroy')->name('destroy');
            // Route::get('progress', 'progress')->name('progress');
            Route::patch('{id}/move', 'move')->name('move');
            // Route::patch('{id}/complete', 'complete')->name('complete');
            // Route::patch('{id}/check', 'check')->name('check');
        });
        // Route - route untuk TaskFile di dalam "/tasks"
        // Route::prefix('{task_id}/files')
        //     ->name('files.')
        //     ->controller(TaskFileController::class)
        //     ->group(function () {
        //         Route::post('store', 'store')->name('store');
        //         Route::get('{id}/show', 'show')->name('show');
        //         Route::delete('{id}/destroy', 'destroy')->name('destroy');
        //     });
    });


Route::prefix('{task_id}/files')
    ->name('files.')
    ->controller(TaskFileController::class)
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::post('store', 'store')->name('store');
        Route::get('{id}/show', 'show')->name('show');
        Route::delete('{id}/destroy', 'destroy')->name('destroy');
    });



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
