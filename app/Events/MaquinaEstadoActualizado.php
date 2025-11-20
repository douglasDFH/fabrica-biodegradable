<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\MaquinaEstadoVivo;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MaquinaEstadoActualizado implements ShouldBroadcastNow
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
            new Channel('maquina.' . $this->estado->maquina_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'estado.actualizado';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'estado' => $this->estado,
        ];
    }
}
