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
    public function registrarProduccion(int $maquinaId, float $kgIncremento, float $oee, float $velocidad): array
    {
        return DB::transaction(function () use ($maquinaId, $kgIncremento, $oee, $velocidad) {
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

            // Actualizar estado
            $estado->kg_producidos += $kgIncremento;
            $estado->oee_actual = $oee;
            $estado->velocidad_actual = $velocidad;
            $estado->save();

            // Emitir evento para broadcasting del estado de máquina
            event(new EstadoActualizado($estado));

            // Crear registro de producción
            $produccion = Produccion::create([
                'numero_orden' => 'SIM-'.(int) (microtime(true) * 1000000).'-'.$maquinaId,
                'maquina_id' => $maquinaId,
                'operador_id' => \App\Models\User::first()?->id,
                'receta_id' => \App\Models\Receta::first()?->id,
                'cantidad_producida_kg' => $kgIncremento,
                'oee_actual' => $oee,
                'velocidad_actual' => $velocidad,
                'estado' => 'Finalizada',
                'fecha_inicio' => now(),
                'fecha_fin' => now(),
                'turno' => $this->determinarTurno(),
            ]);

            // Emitir evento para broadcasting
            event(new Registrada($produccion, $estado));

            return [
                'maquina_id' => $maquinaId,
                'kg_producidos' => $estado->kg_producidos,
                'oee_actual' => $estado->oee_actual,
                'velocidad_actual' => $estado->velocidad_actual,
                'estado' => 'actualizado',
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

    private function determinarTurno(): string
    {
        $hora = now()->hour;

        if ($hora >= 6 && $hora < 14) {
            return 'Diurno';
        } elseif ($hora >= 14 && $hora < 22) {
            return 'Vespertino';
        } else {
            return 'Nocturno';
        }
    }
}
