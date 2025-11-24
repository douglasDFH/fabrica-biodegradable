<?php

namespace App\Http\Controllers;

use App\Models\Maquina;
use App\Models\TipoMaquina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MaquinaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $maquinas = Maquina::with('tipo')->get();

        return Inertia::render('Maquinas/Index', [
            'maquinas' => $maquinas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipos = TipoMaquina::all();

        return Inertia::render('Maquinas/Create', [
            'tipos' => $tipos,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:255|unique:maquinas,codigo',
            'nombre' => 'required|string|max:255',
            'tipo_maquina_id' => 'required|exists:tipos_maquinas,id',
            'fecha_compra' => 'nullable|date',
            'horas_trabajadas' => 'nullable|numeric|min:0',
            'estado' => 'required|in:Operativa,Mantenimiento,Fuera de servicio',
            'ubicacion_planta' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Procesar la foto si existe
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $nombreArchivo = time() . '_' . $foto->getClientOriginalName();
            $ruta = $foto->storeAs('maquinas', $nombreArchivo, 'public');
            $validated['foto'] = $ruta;
        }

        Maquina::create($validated);

        return redirect()->route('maquinas.index')
            ->with('success', 'Máquina creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Maquina $maquina)
    {
        $maquina->load('tipo');

        return Inertia::render('Maquinas/Show', [
            'maquina' => $maquina,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maquina $maquina)
    {
        $maquina->load('tipo');
        $tipos = TipoMaquina::all();

        return Inertia::render('Maquinas/Edit', [
            'maquina' => $maquina,
            'tipos' => $tipos,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maquina $maquina)
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:255|unique:maquinas,codigo,' . $maquina->id,
            'nombre' => 'required|string|max:255',
            'tipo_maquina_id' => 'required|exists:tipos_maquinas,id',
            'fecha_compra' => 'nullable|date',
            'horas_trabajadas' => 'nullable|numeric|min:0',
            'estado' => 'required|in:Operativa,Mantenimiento,Fuera de servicio',
            'ubicacion_planta' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Procesar la foto si existe
        if ($request->hasFile('foto')) {
            // Eliminar la foto anterior si existe
            if ($maquina->foto) {
                Storage::disk('public')->delete($maquina->foto);
            }

            $foto = $request->file('foto');
            $nombreArchivo = time() . '_' . $foto->getClientOriginalName();
            $ruta = $foto->storeAs('maquinas', $nombreArchivo, 'public');
            $validated['foto'] = $ruta;
        }

        $maquina->update($validated);

        return redirect()->route('maquinas.index')
            ->with('success', 'Máquina actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maquina $maquina)
    {
        // Eliminar la foto si existe
        if ($maquina->foto) {
            Storage::disk('public')->delete($maquina->foto);
        }

        $maquina->delete();

        return redirect()->route('maquinas.index')
            ->with('success', 'Máquina eliminada exitosamente.');
    }
}
