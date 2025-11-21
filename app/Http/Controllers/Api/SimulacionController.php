<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\ProduccionServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SimulacionController extends Controller
{
    protected ProduccionServiceInterface $produccionService;

    public function __construct(ProduccionServiceInterface $produccionService)
    {
        $this->produccionService = $produccionService;
    }

    public function simularProduccion(Request $request)
    {
        try {
            $request->validate([
                'producciones' => 'required|array',
                'producciones.*.maquina_id' => 'required|integer|exists:maquinas,id',
                'producciones.*.kg_incremento' => 'required|numeric|min:0|max:9999.99',
                'producciones.*.oee' => 'required|numeric|min:0|max:100',
                'producciones.*.velocidad' => 'required|numeric|min:0|max:9999.99',
                'producciones.*.timestamp_generacion' => 'nullable|numeric',
                'producciones.*.is_last_register' => 'nullable|boolean',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Errores de validación:', $e->errors());

            return response()->json(['errors' => $e->errors()], 422);
        }

        $resultados = [];

        foreach ($request->producciones as $prod) {
            // Usar timestamp_generacion si existe, sino usar now()
            $fechaProduccion = isset($prod['timestamp_generacion'])
                ? \Carbon\Carbon::createFromTimestampMs($prod['timestamp_generacion'])
                : now();

            $isLastRegister = $prod['is_last_register'] ?? false;

            $resultado = $this->produccionService->registrarProduccion(
                $prod['maquina_id'],
                $prod['kg_incremento'],
                $prod['oee'],
                $prod['velocidad'],
                $fechaProduccion,
                $isLastRegister
            );
            $resultados[] = $resultado;
        }

        return response()->json([
            'success' => true,
            'message' => 'Producciones simuladas exitosamente',
            'resultados' => $resultados,
        ]);
    }
}
