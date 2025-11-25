<?php

namespace App\Http\Controllers;

use App\Models\Maquina;
use App\Services\Contracts\ProduccionServiceInterface;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        private ProduccionServiceInterface $produccionService
    ) {}

    public function index()
    {
        // Dashboard en tiempo real con estadísticas
        $estadisticas = $this->produccionService->getEstadisticasDia();

        // Obtener producción por máquina
        $produccionPorMaquina = $this->produccionService->getProduccionPorMaquina();

        // Obtener estados de todas las máquinas
        $estadosMaquinas = Maquina::with(['estadoVivo', 'tipo'])
            ->get()
            ->map(function ($maquina) {
                return [
                    'maquina_id' => $maquina->id,
                    'maquina' => [
                        'id' => $maquina->id,
                        'nombre' => $maquina->nombre,
                        'codigo' => $maquina->codigo,
                        'tipo' => $maquina->tipo?->nombre,
                    ],
                    'estado' => $maquina->estadoVivo?->estado ?? 'Offline',
                    'kg_producidos' => $maquina->estadoVivo?->kg_producidos ?? 0,
                    'oee_actual' => $maquina->estadoVivo?->oee_actual ?? 0,
                    'velocidad_actual' => $maquina->estadoVivo?->velocidad_actual ?? 0,
                ];
            });

        return Inertia::render('Dashboard', [
            'estadisticas' => $estadisticas,
            'produccionPorMaquina' => $produccionPorMaquina,
            'estadosMaquinas' => $estadosMaquinas,
        ]);
    }
}
