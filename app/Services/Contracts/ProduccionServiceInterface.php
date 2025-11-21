<?php

namespace App\Services\Contracts;

use Carbon\Carbon;

interface ProduccionServiceInterface
{
    /**
     * Registrar una producción simulada
     */
    public function registrarProduccion(int $maquinaId, float $kgIncremento, float $oee, float $velocidad, ?Carbon $fechaProduccion = null, bool $isLastRegister = false): array;

    /**
     * Obtener estadísticas de producción del día
     */
    public function getEstadisticasDia(): array;

    /**
     * Obtener producción por máquina del día
     */
    public function getProduccionPorMaquina(): array;
}
