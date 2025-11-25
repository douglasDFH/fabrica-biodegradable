<?php

namespace Database\Seeders;

use App\Models\TipoMaquina;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoMaquinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['nombre' => 'Extrusora', 'descripcion' => 'Máquina para extrusión de plástico biodegradable'],
            ['nombre' => 'Inyectora', 'descripcion' => 'Máquina de inyección para moldes'],
            ['nombre' => 'Selladora', 'descripcion' => 'Máquina para sellado térmico de bolsas'],
            ['nombre' => 'Cortadora', 'descripcion' => 'Máquina de corte industrial'],
            ['nombre' => 'Impresora', 'descripcion' => 'Impresora flexográfica para empaques'],
            ['nombre' => 'Mezcladora', 'descripcion' => 'Mezcladora de resinas biodegradables'],
        ];

        foreach ($tipos as $tipo) {
            TipoMaquina::create($tipo);
        }
    }
}
