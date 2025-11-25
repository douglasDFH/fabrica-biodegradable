<?php

namespace Database\Seeders;

use App\Models\Maquina;
use App\Models\TipoMaquina;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaquinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = TipoMaquina::all();

        if ($tipos->isEmpty()) {
            $this->command->warn('No hay tipos de máquinas. Ejecuta TipoMaquinaSeeder primero.');
            return;
        }

        $maquinas = [
            [
                'codigo' => 'MQ-1001',
                'nombre' => 'Extrusora Principal A',
                'tipo_maquina_id' => $tipos->where('nombre', 'Extrusora')->first()->id,
                'fecha_compra' => '2020-01-15',
                'horas_trabajadas' => 5200.50,
                'estado' => 'Operativa',
                'ubicacion_planta' => 'Planta Principal - Sección A',
            ],
            [
                'codigo' => 'MQ-1002',
                'nombre' => 'Inyectora B1',
                'tipo_maquina_id' => $tipos->where('nombre', 'Inyectora')->first()->id,
                'fecha_compra' => '2021-03-20',
                'horas_trabajadas' => 3100.75,
                'estado' => 'Operativa',
                'ubicacion_planta' => 'Planta Principal - Sección B',
            ],
            [
                'codigo' => 'MQ-1003',
                'nombre' => 'Selladora Automática S1',
                'tipo_maquina_id' => $tipos->where('nombre', 'Selladora')->first()->id,
                'fecha_compra' => '2019-06-10',
                'horas_trabajadas' => 7800.00,
                'estado' => 'Mantenimiento',
                'ubicacion_planta' => 'Zona de Empaque',
            ],
            [
                'codigo' => 'MQ-1004',
                'nombre' => 'Cortadora Industrial C2',
                'tipo_maquina_id' => $tipos->where('nombre', 'Cortadora')->first()->id,
                'fecha_compra' => '2022-02-28',
                'horas_trabajadas' => 1500.25,
                'estado' => 'Operativa',
                'ubicacion_planta' => 'Almacén de Producción',
            ],
            [
                'codigo' => 'MQ-1005',
                'nombre' => 'Impresora Flexográfica IF-3',
                'tipo_maquina_id' => $tipos->where('nombre', 'Impresora')->first()->id,
                'fecha_compra' => '2021-09-15',
                'horas_trabajadas' => 2200.00,
                'estado' => 'Operativa',
                'ubicacion_planta' => 'Planta Secundaria',
            ],
            [
                'codigo' => 'MQ-1006',
                'nombre' => 'Mezcladora de Resinas M1',
                'tipo_maquina_id' => $tipos->where('nombre', 'Mezcladora')->first()->id,
                'fecha_compra' => '2020-11-05',
                'horas_trabajadas' => 4500.50,
                'estado' => 'Operativa',
                'ubicacion_planta' => 'Planta Principal - Sección A',
            ],
        ];

        foreach ($maquinas as $maquina) {
            Maquina::create($maquina);
        }
    }
}
