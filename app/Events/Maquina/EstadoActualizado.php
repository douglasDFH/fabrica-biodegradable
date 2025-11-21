<?php

namespace App\Events\Maquina;

use App\Models\MaquinaEstadoVivo;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EstadoActualizado implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public MaquinaEstadoVivo $estado;

    /**
     * Create a new event instance.
     */
    public function __construct(MaquinaEstadoVivo $estado)
    {
        $this->estado = $estado;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('dashboard'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'maquina.estado.actualizado';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        try {
            $produccionService = app(\App\Services\Contracts\ProduccionServiceInterface::class);

            return [
                'estado' => $this->estado->load('maquina'),
                'estadisticas' => $produccionService->getEstadisticasDia(),
                'produccionPorMaquina' => $produccionService->getProduccionPorMaquina(),
            ];
        } catch (\Exception $e) {
            Log::error('Error en EstadoActualizado::broadcastWith: '.$e->getMessage());

            // Retornar datos mínimos en caso de error
            return [
                'estado' => $this->estado->load('maquina'),
                'estadisticas' => [
                    'total_kg' => 0,
                    'total_producciones' => 0,
                    'maquinas_activas' => 0,
                    'oee_promedio' => 0,
                ],
                'produccionPorMaquina' => [],
            ];
        }
    }
}
