<?php

use App\Http\Controllers\Planta\MonitorMaquinaController;
use Illuminate\Support\Facades\Route;

Route::get('/planta/monitor-maquina', [MonitorMaquinaController::class, 'index'])
    ->name('planta.monitor-maquina.index');

Route::get('/planta/monitor-maquina/{maquina}', [MonitorMaquinaController::class, 'show'])
    ->name('planta.monitor-maquina.show');

Route::get('/api/maquina/{maquina}/estado', [MonitorMaquinaController::class, 'getEstado']);
