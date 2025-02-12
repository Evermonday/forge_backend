<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Models\Scenario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//TODO: Implement throttle

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');
    
# Project
Route::get('/project', [ProjectController::class, 'index'])
    ->middleware('auth:sanctum');
Route::put('/project/landArea', [ProjectController::class, 'updateLandArea'])
    ->middleware('auth:sanctum');

# Overview
Route::get('/scenario/{scenario}', [ScenarioController::class, 'show'])
    ->middleware('auth:sanctum');
// Route::put('/scenario/{scenario}', [ScenarioController::class, 'update'])
//     ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/name', [ScenarioController::class, 'updateName'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/note', [ScenarioController::class, 'updateNote'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/tag', [ScenarioController::class, 'updateTag'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/developmentType', [ScenarioController::class, 'updateDevelopmentType'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/developmentStrategy', [ScenarioController::class, 'updateDevelopmentStrategy'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/unitType', [ScenarioController::class, 'updateUnitType'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/endUse', [ScenarioController::class, 'updateEndUse'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/gfaCalcMethod', [ScenarioController::class, 'updateGFACalcMethod'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/fsi', [ScenarioController::class, 'updateFSI'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/gfa', [ScenarioController::class, 'updateGFA'])
    ->middleware('auth:sanctum');

Route::put('/scenario/{scenario}/areaAllocMethod', [ScenarioController::class, 'areaAllocMethod'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/residentialGFANumber', [ScenarioController::class, 'residentialGFANumber'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/residentialGFAPercentage', [ScenarioController::class, 'residentialGFAPercentage'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/commercialGFANumber', [ScenarioController::class, 'commercialGFANumber'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/commercialGFAPercentage', [ScenarioController::class, 'commercialGFAPercentage'])
    ->middleware('auth:sanctum');

Route::put('/scenario/{scenario}/nfaAreaAllocMethod', [ScenarioController::class, 'nfaAreaAllocMethod'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/residentialNFANumber', [ScenarioController::class, 'residentialNFANumber'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/residentialNFAPercentage', [ScenarioController::class, 'residentialNFAPercentage'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/commercialNFANumber', [ScenarioController::class, 'commercialNFANumber'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/commercialNFAPercentage', [ScenarioController::class, 'commercialNFAPercentage'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/commercialNFAPercentage', [ScenarioController::class, 'commercialNFAPercentage'])
    ->middleware('auth:sanctum');

Route::get('/scenario', [ScenarioController::class, 'index'])
    ->middleware('auth:sanctum');
Route::post('/scenario', [ScenarioController::class, 'fromScratch'])
    ->middleware('auth:sanctum');

# Task-Related Scenario Actions
Route::post('/scenario/{scenario}/task', [ScenarioController::class, 'createBlankTask'])
    ->middleware('auth:sanctum');
Route::put('/scenario/{scenario}/taskDisplayIds', [ScenarioController::class, 'updateTaskDisplayIds'])
    ->middleware('auth:sanctum');


# Schedule
Route::get('/scenario/{scenario}/task', [TaskController::class, 'index'])
    ->middleware('auth:sanctum');

Route::put('/scenario/{scenario}/startDate', [ScenarioController::class, 'updateStartDate'])
    ->middleware('auth:sanctum');
Route::put('/task/{task}/name', [TaskController::class, 'updateTaskName'])
    ->middleware('auth:sanctum');
Route::put('/task/{task}/mode', [TaskController::class, 'updateTaskMode'])
    ->middleware('auth:sanctum');
Route::put('/task/{task}/startDate', [TaskController::class, 'updateTaskStartDate'])
    ->middleware('auth:sanctum');
Route::put('/task/{task}/duration', [TaskController::class, 'updateTaskDuration'])
    ->middleware('auth:sanctum');
Route::put('/task/{task}/predecessors', [TaskController::class, 'updateTaskPredecessor'])
    ->middleware('auth:sanctum');

# Tag
Route::get('/tag', [TagController::class, 'index'])
    ->middleware('auth:sanctum');
// Route::post('/tag', [TagController::class, 'store'])
//     ->middleware('auth:sanctum');
Route::put('/tag/{tag}', [TagController::class, 'update'])
    ->middleware('auth:sanctum');
