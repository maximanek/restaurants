<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RestaurantController;

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

Route::name('api.')->group(function () {
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(UserController::class)
            ->prefix('/users')
            ->name('user.')
            ->group(function () {
                Route::post('', 'store')->name('store');
                Route::patch('/{id}', 'update')->name('update');
                Route::get('/{id}', 'show')->name('show');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
        Route::resource('/restaurants', RestaurantController::class);
        Route::patch('/restaurants/{id}/user', [RestaurantController::class, 'manageUsers']);
        Route::controller(NoteController::class)
            ->prefix('/notes')
            ->name('notes.')
            ->group(function () {
                Route::get('', 'index')->name('index');
                Route::post('', 'store')->name('store');
            });
    });
});
