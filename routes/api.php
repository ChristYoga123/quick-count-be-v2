<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DapilController;
use App\Http\Controllers\API\PillegController;
use App\Http\Controllers\API\PilparController;
use App\Http\Controllers\API\PilpresController;
use App\Http\Controllers\API\SurveyController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('pilpres')->group(function () {
    Route::get('/', [PilpresController::class, 'index']);
    Route::post('submit-suara', [PilpresController::class, 'submitSuara']);
});

Route::prefix('pilpar')->group(function () {
    Route::get('/', [PilparController::class, 'index']);
    Route::post('submit-suara', [PilparController::class, 'submitSuara']);
});

Route::prefix('pilleg')->group(function () {
    Route::get('/{partai}/{dapil}', [PillegController::class, 'index']);
    Route::post('submit-suara', [PillegController::class, 'submitSuara']);
});

Route::prefix('survey')->group(function () {
    Route::get('/', [SurveyController::class, 'index']);
    Route::get('/{surveyTitle}', [SurveyController::class, 'show']);
    Route::post('answer', [SurveyController::class, 'answer']);
});

Route::middleware('api')->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('me', [AuthController::class, 'me']);
});

Route::get('dapil', [DapilController::class, 'index']);
