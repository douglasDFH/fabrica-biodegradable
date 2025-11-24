<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaquinaController;
use App\Http\Controllers\Planta\MonitorMaquinaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// CRUD de Máquinas
Route::resource('maquinas', MaquinaController::class);

// Rutas de Monitor de Máquinas
Route::get('/planta/monitor-maquina', [MonitorMaquinaController::class, 'index'])
    ->name('planta.monitor-maquina.index');

Route::get('/planta/monitor-maquina/{maquina}', [MonitorMaquinaController::class, 'show'])
    ->name('planta.monitor-maquina.show');

Route::get('/planta/monitor-maquina/{maquina}/preview', [MonitorMaquinaController::class, 'preview'])
    ->name('planta.monitor-maquina.preview');
