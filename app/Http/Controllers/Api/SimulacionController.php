<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Maquina;
use App\Models\MaquinaEstadoVivo;
use App\Events\MaquinaEstadoActualizado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SimulacionController extends Controller
{
    public function simularProduccion(Request $request)
    {
        Log::info('Datos recibidos en simularProduccion:', $request->all());

        try {
            $request->validate([
                'producciones' => 'required|array',
                'producciones.*.maquina_id' => 'required|integer|exists:maquinas,id',
                'producciones.*.kg_incremento' => 'required|numeric|min:0',
                'producciones.*.oee' => 'required|numeric|min:0|max:100',
                'producciones.*.velocidad' => 'required|numeric|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Errores de validación:', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        }

        $resultados = [];

        foreach ($request->producciones as $prod) {
            $maquinaId = $prod['maquina_id'];
            $kgIncremento = $prod['kg_incremento'];
            $oee = $prod['oee'];
            $velocidad = $prod['velocidad'];

            $maquina = Maquina::findOrFail($maquinaId);

            // Obtener o crear estado vivo
            $estado = MaquinaEstadoVivo::firstOrCreate(
                ['maquina_id' => $maquinaId],
                [
                    'estado' => 'Produciendo',
                    'kg_producidos' => 0,
                    'velocidad_actual' => 100,
                    'oee_actual' => 85.5,
                ]
            );

            // Actualizar estado
            $estado->kg_producidos += $kgIncremento;
            $estado->oee_actual = $oee;
            $estado->velocidad_actual = $velocidad;
            $estado->save();

            // Broadcast el evento
            broadcast(new MaquinaEstadoActualizado($estado));

            $resultados[] = [
                'maquina_id' => $maquinaId,
                'kg_producidos' => $estado->kg_producidos,
                'oee_actual' => $estado->oee_actual,
                'velocidad_actual' => $estado->velocidad_actual,
                'estado' => 'actualizado',
            ];
        }

        return response()->json([
            'success' => true,
            'message' => 'Producciones simuladas exitosamente',
            'resultados' => $resultados,
        ]);
    }
}