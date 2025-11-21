<?php

namespace App\Services;

use App\Events\Maquina\EstadoActualizado;
use App\Events\Produccion\Registrada;
use App\Models\Maquina;
use App\Models\MaquinaEstadoVivo;
use App\Models\Produccion;
use App\Services\Contracts\ProduccionServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProduccionService implements ProduccionServiceInterface
{
    public function registrarProduccion(int $maquinaId, float $kgIncremento, float $oee, float $velocidad, ?Carbon $fechaProduccion = null): array
    {
        return DB::transaction(function () use ($maquinaId, $kgIncremento, $oee, $velocidad, $fechaProduccion) {
            $fechaProduccion = $fechaProduccion ?? now();
            $turno = $this->determinarTurno($fechaProduccion);
            $maquina = Maquina::findOrFail($maquinaId);

            // Obtener o crear estado vivo
            $estado = MaquinaEstadoVivo::firstOrCreate(
                ['maquina_id' => $maquina->id],
                [
                    'estado' => 'Produciendo',
                    'kg_producidos' => 0,
                    'velocidad_actual' => 100,
                    'oee_actual' => 85.5,
                ]
            );

            // IMPORTANTE: Buscar producción activa del turno actual
            // No crear una nueva cada vez, sino actualizar la existente
            $produccion = Produccion::firstOrCreate(
                [
                    'maquina_id' => $maquinaId,
                    'turno' => $turno,
                    'estado' => 'EnCurso', // Solo producciones activas
                ],
                [
                    // Datos iniciales si se crea nueva
                    'numero_orden' => 'ORD-'.$fechaProduccion->format('Ymd').'-'.$maquinaId.'-'.$turno,
                    'operador_id' => \App\Models\User::first()?->id,
                    'receta_id' => \App\Models\Receta::first()?->id,
                    'cantidad_producida_kg' => 0,
                    'fecha_inicio' => $fechaProduccion,
                    'oee_actual' => $oee,
                    'velocidad_actual' => $velocidad,
                ]
            );

            // Incrementar cantidad producida
            $produccion->cantidad_producida_kg += $kgIncremento;

            // Actualizar métricas (promedio móvil o última lectura)
            $produccion->oee_actual = $oee;
            $produccion->velocidad_actual = $velocidad;
            $produccion->fecha_fin = $fechaProduccion; // Actualizar última actividad

            $produccion->save();

            // Actualizar estado vivo de la máquina
            $estado->kg_producidos += $kgIncremento;
            $estado->oee_actual = $oee;
            $estado->velocidad_actual = $velocidad;
            $estado->produccion_id = $produccion->id;
            $estado->save();

            // Emitir evento para broadcasting del estado de máquina
            event(new EstadoActualizado($estado));

            // Emitir evento para broadcasting
            event(new Registrada($produccion, $estado));

            return [
                'maquina_id' => $maquinaId,
                'kg_producidos' => $estado->kg_producidos,
                'oee_actual' => $estado->oee_actual,
                'velocidad_actual' => $estado->velocidad_actual,
                'estado' => 'actualizado',
                'produccion_id' => $produccion->id,
            ];
        });
    }

    public function getEstadisticasDia(): array
    {
        $hoy = Carbon::today();

        $produccionesHoy = Produccion::whereDate('created_at', $hoy)->get();

        return [
            'total_kg' => $produccionesHoy->sum('cantidad_producida_kg'),
            'total_producciones' => $produccionesHoy->count(),
            'maquinas_activas' => $produccionesHoy->pluck('maquina_id')->unique()->count(),
            'oee_promedio' => round($produccionesHoy->avg('oee_actual') ?? 0, 2),
        ];
    }

    public function getProduccionPorMaquina(): array
    {
        $hoy = Carbon::today();

        return Produccion::whereDate('created_at', $hoy)
            ->with('maquina')
            ->selectRaw('maquina_id, SUM(cantidad_producida_kg) as total_kg, COUNT(*) as producciones, AVG(oee_actual) as oee_promedio')
            ->groupBy('maquina_id')
            ->get()
            ->map(function ($item) {
                return [
                    'maquina' => $item->maquina,
                    'total_kg' => $item->total_kg,
                    'producciones' => $item->producciones,
                    'oee_promedio' => round($item->oee_promedio, 2),
                ];
            })
            ->toArray();
    }

    private function determinarTurno(?Carbon $fecha = null): string
    {
        $fecha = $fecha ?? now();
        $hora = $fecha->hour;

        if ($hora >= 6 && $hora < 14) {
            return 'Mañana';
        } elseif ($hora >= 14 && $hora < 22) {
            return 'Tarde';
        } else {
            return 'Noche';
        }
    }
}
