<?php

use App\Http\Controllers\Api\V1\AppointmentController as V1AppointmentController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ServicesController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\ServiceBarberController;
use App\Http\Controllers\Api\V1\ServiceSchedule;
use App\Http\Controllers\Api\V1\UsersController;
use App\Models\HorariosBarbero;
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

    //Users routes
    Route::get('/usuarios', [UsersController::class, 'index']);
    Route::post('/usuarios/store', [UsersController::class, 'store']);
    Route::get('/usuarios/search/{nombre}', [UsersController::class, 'showNombre']);
    Route::get('/usuarios/barberos', [UsersController::class, 'showBarbero']);
    
    //Services routes
    Route::get('/services', [ServicesController::class, 'index']);
    Route::post('/services/store', [ServicesController::class, 'store']);
    Route::put('/services/put/{id}', [ServicesController::class, 'update']);
    Route::delete('/services/delete/{id}', [ServicesController::class, 'delete']);
    Route::get('/services/search/{nombre}', [ServicesController::class, 'showNombre']);

    //Appointments routes
    Route::get('/citas', [AppointmentController::class, 'index']);
    Route::post('/citas/store', [AppointmentController::class, 'store']);
    Route::put('/citas/put/{id}', [AppointmentController::class, 'update']);
    Route::delete('/citas/delete/{id}', [AppointmentController::class, 'delete']);
    Route::get('/citas/searchClienteCita', [AppointmentController::class, 'showClienteCita']);
    Route::get('/citas/searchBarberoCita', [AppointmentController::class, 'showBarberoCita']);
    Route::get('/citas/searchFechaCita', [AppointmentController::class, 'showFechaCita']);

    //Service barber routes
    Route::get('/BarberoServicio', [ServiceBarberController::class, 'index']);
    Route::post('/BarberoServicio/store', [ServiceBarberController::class, 'store']);
    Route::delete('/BarberoServicio/delete/{id}', [ServiceBarberController::class, 'delete']);

    //Schedule service routes
    Route::get('/HorariosBarbero', [ServiceSchedule::class, 'index']);
    Route::post('/HorariosBarbero/store', [ServiceSchedule::class, 'store']);
    Route::put('/HorariosBarbero/put/{id}', [ServiceSchedule::class, 'update']);
    Route::delete('/HorariosBarbero/delete/{id}', [ServiceSchedule::class, 'delete']);
    Route::get('HorariosBarbero/semana', [ServiceSchedule::class, 'semana']);
});
