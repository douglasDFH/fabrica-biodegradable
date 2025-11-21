<?php

namespace App\Events\Produccion;

use App\Models\MaquinaEstadoVivo;
use App\Models\Produccion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Registrada implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Produccion $produccion;

    public MaquinaEstadoVivo $estado;

    /**
     * Create a new event instance.
     */
    public function __construct(Produccion $produccion, MaquinaEstadoVivo $estado)
    {
        $this->produccion = $produccion;
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
        return 'produccion.registrada';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'produccion' => $this->produccion->load('maquina'),
            'estado' => $this->estado->load('maquina'),
            'estadisticas' => app(\App\Services\Contracts\ProduccionServiceInterface::class)->getEstadisticasDia(),
            'produccionPorMaquina' => app(\App\Services\Contracts\ProduccionServiceInterface::class)->getProduccionPorMaquina(),
        ];
    }
}
