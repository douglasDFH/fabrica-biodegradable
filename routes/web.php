<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\Planta\MonitorMaquinaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/welcome');
});

Route::get('/welcome', [App\Http\Controllers\WelcomeController::class, 'index'])
    ->name('welcome');

// Rutas de Autenticación Públicas
Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'authenticate']);

// Rutas Protegidas
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // CRUD de Máquinas
    Route::resource('maquinas', MaquinaController::class);

    // Rutas de Monitor de Máquinas
    Route::get('/planta/monitor-maquina', [MonitorMaquinaController::class, 'index'])
        ->name('planta.monitor-maquina.index');

    Route::get('/planta/monitor-maquina/{maquina}', [MonitorMaquinaController::class, 'show'])
        ->name('planta.monitor-maquina.show');
});
