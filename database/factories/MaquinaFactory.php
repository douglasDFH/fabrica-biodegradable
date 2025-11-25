<?php

namespace Database\Factories;

use App\Models\TipoMaquina;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Maquina>
 */
class MaquinaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'codigo' => 'MQ-' . fake()->unique()->numberBetween(1000, 9999),
            'nombre' => fake()->randomElement([
                'Extrusora Principal',
                'Inyectora A1',
                'Selladora Automática',
                'Cortadora Industrial',
                'Impresora Flexográfica',
                'Mezcladora de Resinas',
            ]) . ' ' . fake()->numberBetween(1, 10),
            'tipo_maquina_id' => TipoMaquina::factory(),
            'fecha_compra' => fake()->dateTimeBetween('-5 years', '-1 year'),
            'horas_trabajadas' => fake()->randomFloat(2, 0, 10000),
            'estado' => fake()->randomElement(['Operativa', 'Mantenimiento', 'Fuera de servicio']),
            'ubicacion_planta' => fake()->randomElement([
                'Planta Principal - Sección A',
                'Planta Principal - Sección B',
                'Planta Secundaria',
                'Almacén de Producción',
                'Zona de Empaque',
            ]),
        ];
    }
}
