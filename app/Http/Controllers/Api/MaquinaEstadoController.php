<?php

namespace App\Http\Controllers\Api;

use App\Events\Maquina\EstadoActualizado;
use App\Http\Controllers\Controller;
use App\Models\Maquina;
use App\Models\MaquinaEstadoVivo;
use Illuminate\Http\Request;

class MaquinaEstadoController extends Controller
{
    /**
     * Actualizar el estado de una máquina
     */
    public function updateEstado(Request $request, Maquina $maquina)
    {
        $validated = $request->validate([
            'estado' => 'required|in:Produciendo,Pausada,Parada,Offline',
        ]);

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
    }
}
