<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CAPDController;
use App\Http\Controllers\ModuleController;
use Illuminate\Http\Request;
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

// Authentication routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/auth/get-auth', [AuthController::class, 'getAuth']);

    Route::get('/modules', [ModuleController::class, 'index']);
    Route::get('/modules/type', [ModuleController::class, 'getByType']);
    Route::get('/modules/{id}', [ModuleController::class, 'show']);

    // CAPD public routes (read access)
    Route::get('/capds', [CAPDController::class, 'index']);
    Route::get('/capds/{id}', [CAPDController::class, 'show']);

    Route::middleware(['role:admin'])->group(function () {
        // Module admin routes
        Route::post('/modules', [ModuleController::class, 'store']);
        Route::put('/modules/{id}', [ModuleController::class, 'update']);
        Route::delete('/modules/{id}', [ModuleController::class, 'destroy']);

        // CAPD admin routes
        Route::post('/capds', [CAPDController::class, 'store']);
        Route::put('/capds/{id}', [CAPDController::class, 'update']);
        Route::delete('/capds/{id}', [CAPDController::class, 'destroy']);
    });
});
