<?php

use App\Http\Controllers\AdminAppointmentController;
use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/admin/login', [AdminUsersController::class, 'index']);
Route::get('/dashboard', [AdminUsersController::class, 'dashboardIndex'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/**RUTAS PARA ADMIN USER */
Route::post('/admin/users/store', [AdminUsersController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('admin.users.store');
Route::delete('/admin/users/delete/{id}', [AdminUsersController::class, 'delete'])
    ->middleware(['auth', 'verified'])
    ->name('admin.users.delete');
Route::put('/admin/users/update/{id}', [AdminUsersController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('admin.users.update');
Route::get('/admin/users/get', [AdminUsersController::class, 'showUser'])
    ->middleware(['auth', 'verified'])
    ->name('admin.users.show');
Route::get('/admin/users/export', [AdminUsersController::class, 'export'])
    ->middleware(['auth', 'verified'])
    ->name('admin.users.export');

/**RUTAS PARA ADMIN SERVICES */
Route::post('/admin/services/store', [AdminServiceController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('admin.service.store');
Route::delete('/admin/services/delete/{id}', [AdminServiceController::class, 'delete'])
    ->middleware(['auth', 'verified'])
    ->name('admin.service.delete');
Route::put('/admin/services/update/{id}', [AdminServiceController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('admin.service.update');
Route::get('/admin/services/get', [AdminServiceController::class, 'showServices'])
    ->middleware(['auth', 'verified'])
    ->name('admin.service.show');
Route::get('/admin/services/export', [AdminServiceController::class, 'export'])
    ->middleware(['auth', 'verified'])
    ->name('admin.service.export');

/**RUTAS PARA ADMIN APPOINTMENT*/
Route::post('/admin/citas/store', [AdminAppointmentController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('admin.citas.store');
Route::delete('/admin/citas/delete/{id}', [AdminAppointmentController::class, 'delete'])
    ->middleware(['auth', 'verified'])
    ->name('admin.citas.delete');
Route::put('/admin/citas/update/{id}', [AdminAppointmentController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('admin.citas.update');
Route::get('/admin/citas/get', [AdminAppointmentController::class, 'showCitas'])
    ->middleware(['auth', 'verified'])
    ->name('admin.citas.show');
Route::get('/admin/citas/barberos', [AdminAppointmentController::class, 'cargarBarberos'])
    ->middleware(['auth', 'verified'])
    ->name('admin.citas.barberos');
Route::get('/admin/citas/servicios', [AdminAppointmentController::class, 'cargarServicios'])
    ->middleware(['auth', 'verified'])
    ->name('admin.citas.servicios');
Route::get('/admin/citas/export', [AdminAppointmentController::class, 'export'])
    ->middleware(['auth', 'verified'])
    ->name('admin.citas.export');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
