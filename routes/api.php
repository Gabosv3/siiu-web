<?php

use App\Http\Controllers\Api\V1\AssignmentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoriesController;
use App\Http\Controllers\Api\V1\EquipmentHistoryController;
use App\Http\Controllers\Api\V1\HardwareController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Modulos\ReportsController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->group(function () {
    // Puedes agregar otras rutas protegidas aquí
    //CAMBIOOO CLAIRE
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/users', [UserController::class, 'index']);

    Route::get('/hardware', [HardwareController::class, 'index']);
    Route::get('/hardware/{id}', [HardwareController::class, 'show']);
    Route::get('/categories', [CategoriesController::class, 'index']);
    Route::get('/categories/{id}', [CategoriesController::class, 'show']);
    Route::get('/categories/{id}/equipments', [CategoriesController::class, 'getEquipmentsByCategory']);
    Route::get('/equipment-histories', [EquipmentHistoryController::class, 'index']);
    Route::get('/equipment-histories/{id}', [EquipmentHistoryController::class, 'show']);
    Route::get('/equipment-history/{categoryId}/{inventoryCode}', [EquipmentHistoryController::class, 'EquipmentHistory']);
    // Ruta para generar el reporte por usuario (AJAX)
   
});


Route::post('/login', [AuthController::class, 'login']);
