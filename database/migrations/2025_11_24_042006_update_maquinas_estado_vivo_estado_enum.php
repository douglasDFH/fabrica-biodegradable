<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar el ENUM para incluir todos los estados necesarios
        DB::statement("ALTER TABLE maquinas_estado_vivo MODIFY COLUMN estado ENUM('Produciendo', 'Pausada', 'Parada', 'Offline', 'Corriendo', 'Alarma', 'Mantenimiento') NOT NULL DEFAULT 'Parada'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir al ENUM original
        DB::statement("ALTER TABLE maquinas_estado_vivo MODIFY COLUMN estado ENUM('Parada', 'Corriendo', 'Alarma', 'Mantenimiento') NOT NULL DEFAULT 'Parada'");
    }
};
