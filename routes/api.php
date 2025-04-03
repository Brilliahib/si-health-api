<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CAPDController;
use App\Http\Controllers\HDController;
use App\Http\Controllers\ModuleContentController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PostTestController;
use App\Http\Controllers\PreTestController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionSetController;
use App\Http\Controllers\ScreeningController;
use App\Http\Controllers\SubModuleController;
use App\Http\Controllers\UserAnswerPostTestController;
use App\Http\Controllers\UserAnswerPreTestController;
use App\Http\Controllers\UserAnswerScreeningController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserHistoryPostTestController;
use App\Http\Controllers\UserHistoryPreTestController;
use App\Http\Controllers\UserHistoryScreeningController;
use App\Models\UserAnswerPreTest;
use App\Models\UserHistoryScreening;
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

    Route::get('/sub-modules', [SubModuleController::class, 'index']);
    Route::get('/sub-modules/{id}', [SubModuleController::class, 'show']);
    Route::get('/sub-modules/category/{module_id}', [SubModuleController::class, 'getByModule']);

    // Module content public routes (read access)
    Route::get('/module-content', [ModuleContentController::class, 'index']);
    Route::get('/module-content/{id}', [ModuleContentController::class, 'show']);
    Route::get('/module-content/sub/{sub_module_id}', [ModuleContentController::class, 'getBySubModule']);

    // History screening public routes (read access)
    Route::get('/screening/history', [UserHistoryScreeningController::class, 'index']);
    Route::get('/screening/history/{id}', [UserHistoryScreeningController::class, 'show']);

    // Screening public routes (read access)
    Route::get('/screening', [ScreeningController::class, 'index']);
    Route::get('/screening/{id}', [ScreeningController::class, 'show']);

    // Submit screening routes
    Route::post('/screening/submit', [UserAnswerScreeningController::class, 'submit']);

    // History pre test public routes (read access)
    Route::get('/pre-test/history', [UserHistoryPreTestController::class, 'index']);
    Route::get('/pre-test/history/{id}', [UserHistoryPreTestController::class, 'show']);

    // Pre Test public routes (read access)
    Route::get('/pre-test', [PreTestController::class, 'index']);
    Route::get('/pre-test/{id}', [PreTestController::class, 'show']);
    Route::get('/pre-test/sub/{sub_module_id}', [PreTestController::class, 'getBySubModule']);

    // Submit pretest routes
    Route::post('/pre-test/submit', [UserAnswerPreTestController::class, 'submit']);

    // History post test public routes (read access)
    Route::get('/post-test/history', [UserHistoryPostTestController::class, 'index']);
    Route::get('/post-test/history/{id}', [UserHistoryPostTestController::class, 'show']);

    // Post Test public routes (read access)
    Route::get('/post-test', [PostTestController::class, 'index']);
    Route::get('/post-test/{id}', [PostTestController::class, 'show']);

    // Post test public routes (read access)
    Route::get('/post-test', [PostTestController::class, 'index']);
    Route::get('/post-test/{id}', [PostTestController::class, 'show']);
    Route::get('/post-test/sub/{sub_module_id}', [PostTestController::class, 'getBySubModule']);

    // Submit post test routes
    Route::post('/post-test/submit', [UserAnswerPostTestController::class, 'submit']);

    // Question Set public routes (read access)
    Route::get('/question-set', [QuestionSetController::class, 'index']);
    Route::get('/question-set/{id}', [QuestionSetController::class, 'show']);

    // Question public routes (read access)
    Route::get('/question', [QuestionController::class, 'index']);
    Route::get('/question/{id}', [QuestionController::class, 'show']);

    Route::middleware(['role:admin'])->group(function () {
        // Module admin routes
        Route::post('/modules', [ModuleController::class, 'store']);
        Route::put('/modules/{id}', [ModuleController::class, 'update']);
        Route::delete('/modules/{id}', [ModuleController::class, 'destroy']);

        // Sub Module admin routes
        Route::post('/sub-modules', [SubModuleController::class, 'store']);
        Route::put('/sub-modules/{id}', [SubModuleController::class, 'update']);
        Route::delete('/sub-modules/{id}', [SubModuleController::class, 'destroy']);

        // Module content admin routes
        Route::post('/module-content', [ModuleContentController::class, 'store']);
        Route::put('/module-content/{id}', [ModuleContentController::class, 'update']);
        Route::delete('/module-content/{id}', [ModuleContentController::class, 'destroy']);

        // Screening admin routes
        Route::post('/screening', [ScreeningController::class, 'store']);
        Route::put('/screening/{id}', [ScreeningController::class, 'update']);
        Route::delete('/screening/{id}', [ScreeningController::class, 'destroy']);

        // Pre Test admin routes
        Route::post('/pre-test', [PreTestController::class, 'store']);
        Route::put('/pre-test/{id}', [PreTestController::class, 'update']);
        Route::delete('/pre-test/{id}', [PreTestController::class, 'destroy']);

        // Post Test admin routes
        Route::post('/post-test', [PostTestController::class, 'store']);
        Route::put('/post-test/{id}', [PostTestController::class, 'update']);
        Route::delete('/post-test/{id}', [PostTestController::class, 'destroy']);

        // Question Set admin routes
        Route::post('/question-set', [QuestionSetController::class, 'store']);
        Route::put('/question-set/{id}', [QuestionSetController::class, 'update']);
        Route::delete('/question-set/{id}', [QuestionSetController::class, 'destroy']);

        // Question admin routes
        Route::post('/question', [QuestionController::class, 'store']);
        Route::put('/question/{id}', [QuestionController::class, 'update']);
        Route::delete('/question/{id}', [QuestionController::class, 'destroy']);

        // Users admin routes
        Route::apiResource('users', UserController::class);
    });
});
