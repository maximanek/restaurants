<?php

use Illuminate\Support\Facades\Route;
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

Route::post('/login', [LoginController::class, 'login']);
Route::get('/users', [UserController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::controller( UserController::class)->prefix('/users')->group(function () {
        Route::post('','store');
        Route::patch('/{id}','update');
        Route::get('/{id}','show');
        Route::delete('/{id}','destroy');
    });
    Route::resource('/restaurants', RestaurantController::class);
    Route::patch('/restaurants/{id}/user', [RestaurantController::class, 'manageUsers']);
});
