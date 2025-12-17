<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ServicesController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    //Auth routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        //Auth routes
        Route::get('/profile', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    //Services routes
    Route::get('/services', [ServicesController::class, 'index']);
    Route::post('/services/store', [ServicesController::class, 'store']);
    Route::put('/services/put/{id}', [ServicesController::class, 'update']);
    Route::delete('/services/delete/{id}', [ServicesController::class, 'delete']);
    Route::get('/services/search/{nombre}', [ServicesController::class, 'showNombre']);
});
