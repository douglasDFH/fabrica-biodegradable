<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SimulacionController;
use App\Http\Controllers\Planta\MonitorMaquinaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/simular-produccion', [SimulacionController::class, 'simularProduccion']);
Route::get('/maquina/{maquina}/estado', [MonitorMaquinaController::class, 'getEstado']);
