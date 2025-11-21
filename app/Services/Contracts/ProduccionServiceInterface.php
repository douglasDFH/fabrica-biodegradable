<?php

namespace App\Services\Contracts;

interface ProduccionServiceInterface
{
    /**
     * Registrar una producción simulada
     */
    public function registrarProduccion(int $maquinaId, float $kgIncremento, float $oee, float $velocidad, ?\Carbon\Carbon $fechaProduccion = null): array;

    /**
     * Obtener estadísticas de producción del día
     */
    public function getEstadisticasDia(): array;

    /**
     * Obtener producción por máquina del día
     */
    public function getProduccionPorMaquina(): array;
}
