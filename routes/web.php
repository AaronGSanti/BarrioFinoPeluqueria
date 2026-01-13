<?php

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

/**RUTAS PARA ADMIN USER */
Route::get('/dashboard', [AdminUsersController::class, 'usersIndex'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::post('/admin/users/store' , [AdminUsersController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('admin.users.store');
Route::delete('/admin/users/{id}', [AdminUsersController::class, 'delete'])
    ->middleware(['auth', 'verified'])
    ->name('admin.users.delete');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
