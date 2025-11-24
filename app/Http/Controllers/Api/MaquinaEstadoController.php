<?php

namespace App\Http\Controllers\Api;

use App\Events\Maquina\EstadoActualizado;
use App\Http\Controllers\Controller;
use App\Models\Maquina;
use App\Models\MaquinaEstadoVivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MaquinaEstadoController extends Controller
{
    /**
     * Actualizar el estado de una máquina
     */
    public function updateEstado(Request $request, Maquina $maquina)
    {
        $validated = $request->validate([
            'estado' => 'required|in:Produciendo,Pausada,Parada,Offline,Alarma,Mantenimiento',
        ]);

        try {
            // Obtener o crear estado vivo
            $estado = MaquinaEstadoVivo::firstOrCreate(
                ['maquina_id' => $maquina->id],
                [
                    'estado' => $validated['estado'],
                    'kg_producidos' => 0,
                    'velocidad_actual' => 0,
                    'oee_actual' => 0,
                ]
            );

            // Actualizar estado
            $estado->estado = $validated['estado'];

            // Si está en parada u offline, resetear velocidad
            if (in_array($validated['estado'], ['Parada', 'Offline'])) {
                $estado->velocidad_actual = 0;
            }

            $estado->save();

            // Emitir evento para broadcasting
            event(new EstadoActualizado($estado));

            return response()->json([
                'success' => true,
                'message' => 'Estado de máquina actualizado correctamente',
                'estado' => $estado->load('maquina'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error actualizando estado de máquina: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar configuración de simulación
     */
    public function updateSimulacion(Request $request, Maquina $maquina)
    {
        $validated = $request->validate([
            'simulacion_activa' => 'boolean',
            'velocidad_simulacion' => 'numeric|min:0',
            'intervalo_simulacion' => 'integer|min:1',
            'oee_min' => 'numeric|min:0|max:100',
            'oee_max' => 'numeric|min:0|max:100',
            'velocidad_min' => 'numeric|min:0',
            'velocidad_max' => 'numeric|min:0',
        ]);

        $estado = MaquinaEstadoVivo::firstOrCreate(['maquina_id' => $maquina->id]);
        $estado->update($validated);

        return response()->json(['success' => true, 'estado' => $estado]);
    }
}
