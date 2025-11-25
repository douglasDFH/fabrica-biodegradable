<?php

namespace App\Http\Controllers\Planta;

use App\Http\Controllers\Controller;
use App\Models\Maquina;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MonitorMaquinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maquinas = Maquina::with('tipo', 'estadoVivo')->get();

        return Inertia::render('Planta/MonitorMaquinaIndex', [
            'maquinas' => $maquinas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Maquina $maquina)
    {
        $maquina->load('tipo', 'estadoVivo');

        return Inertia::render('Planta/MonitorMaquinaShow_NEW', [
            'maquina' => $maquina,
        ]);
    }

    /**
     * Get detailed info of a machine with date range filtering.
     */
    public function getInfo(Request $request, Maquina $maquina)
    {
        $maquina->load('tipo', 'estadoVivo');

        // Default range: Start of current month to now
        $fechaInicio = $request->input('fecha_inicio') 
            ? \Carbon\Carbon::parse($request->input('fecha_inicio'))->startOfDay()
            : \Carbon\Carbon::now()->startOfMonth();
            
        $fechaFin = $request->input('fecha_fin') 
            ? \Carbon\Carbon::parse($request->input('fecha_fin'))->endOfDay()
            : \Carbon\Carbon::now();

        // Get productions in range
        $producciones = $maquina->producciones()
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                      ->orWhereBetween('fecha_fin', [$fechaInicio, $fechaFin])
                      ->orWhere(function ($q) use ($fechaInicio, $fechaFin) {
                          $q->where('fecha_inicio', '<', $fechaInicio)
                            ->where('fecha_fin', '>', $fechaFin);
                      });
            })
            ->get();

        // Calculate metrics
        $horasTrabajadas = 0;
        $kgProducidos = 0;
        $oeeSum = 0;
        $oeeCount = 0;

        foreach ($producciones as $produccion) {
            // Calculate duration within range
            $inicio = $produccion->fecha_inicio ? \Carbon\Carbon::parse($produccion->fecha_inicio) : null;
            $fin = $produccion->fecha_fin ? \Carbon\Carbon::parse($produccion->fecha_fin) : \Carbon\Carbon::now();

            if ($inicio) {
                // Clamp start and end to the requested range
                $effectiveStart = $inicio->max($fechaInicio);
                $effectiveEnd = $fin->min($fechaFin);

                if ($effectiveEnd->gt($effectiveStart)) {
                    $horasTrabajadas += $effectiveStart->floatDiffInHours($effectiveEnd);
                }
            }

            $kgProducidos += $produccion->cantidad_producida_kg;
            
            if ($produccion->oee_actual) {
                $oeeSum += $produccion->oee_actual;
                $oeeCount++;
            }
        }

        $oeePromedio = $oeeCount > 0 ? ($oeeSum / $oeeCount) : 0;

        return Inertia::render('Planta/ShowInfo', [
            'maquina' => $maquina,
            'metrics' => [
                'horas_trabajadas' => round($horasTrabajadas, 2),
                'kg_producidos' => round($kgProducidos, 2),
                'oee_promedio' => round($oeePromedio, 2),
            ],
            'filters' => [
                'fecha_inicio' => $fechaInicio->format('Y-m-d'),
                'fecha_fin' => $fechaFin->format('Y-m-d'),
            ],
            'producciones' => $producciones->map(function ($p) {
                return [
                    'id' => $p->id,
                    'numero_orden' => $p->numero_orden,
                    'fecha_inicio' => $p->fecha_inicio,
                    'fecha_fin' => $p->fecha_fin,
                    'cantidad_producida_kg' => $p->cantidad_producida_kg,
                    'estado' => $p->estado,
                ];
            }),
        ]);
    }

    /**
     * Get the current state of a machine for API polling.
     */
    public function getEstado(Maquina $maquina)
    {
        $estado = $maquina->estadoVivo;

        if (! $estado) {
            return response()->json(null);
        }

        return response()->json([
            'kg_producidos' => $estado->kg_producidos,
            'oee_actual' => $estado->oee_actual,
            'velocidad_actual' => $estado->velocidad_actual,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maquina $maquina)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maquina $maquina)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maquina $maquina)
    {
        //
    }
}
