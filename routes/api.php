<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');
    
# Scenario
Route::get('/scenario/{scenario}', [ScenarioController::class, 'show'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}', [ScenarioController::class, 'update'])
    ->middleware('auth:sanctum');

Route::get('/scenario', [ScenarioController::class, 'index'])
    ->middleware('auth:sanctum');
Route::post('/scenario', [ScenarioController::class, 'fromScratch'])
    ->middleware('auth:sanctum');

# Tag
Route::get('/tag', [TagController::class, 'index'])
    ->middleware('auth:sanctum');
// Route::post('/tag', [TagController::class, 'store'])
//     ->middleware('auth:sanctum');
Route::put('/tag/{tag}', [TagController::class, 'update'])
    ->middleware('auth:sanctum');
