<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RolController;

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


Route::post('auth/register', [AuthController::class, 'create']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('roles', RolController::class);
    Route::put('roles/delete/{id}', [RolController::class, 'deleteRol']);
    Route::get('roles/show/{id}', [RolController::class, 'showById']);

    Route::resource('menu', MenuController::class);

    Route::get('auth/logout', [AuthController::class, 'logout']);
});
