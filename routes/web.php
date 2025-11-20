<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Planta\MonitorMaquinaController;
use Inertia\Inertia;

Route::get('/planta/monitor-maquina', [MonitorMaquinaController::class, 'index'])
    ->name('planta.monitor-maquina.index');

Route::get('/planta/monitor-maquina/{maquina}', [MonitorMaquinaController::class, 'show'])
    ->name('planta.monitor-maquina.show');

Route::get('/api/maquina/{maquina}/estado', [MonitorMaquinaController::class, 'getEstado']);