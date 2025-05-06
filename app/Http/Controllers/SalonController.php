<?php

namespace App\Http\Controllers;

use App\Models\AsignaDocente;
use App\Models\Docente;
use App\Models\Grado;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalonController extends Controller
{
    public function index(Request $request)
    {
        $docenteId = $request->input('docente_id');
        
        $query = AsignaDocente::with(['docente', 'grado', 'grupo']);

        if ($docenteId) {
            $query->where('docente_id', $docenteId);
        }

        $asignaciones = $query->get()
            ->map(function ($asignacion) {
                return [
                    'id' => $asignacion->id,
                    'docente_id' => $asignacion->docente_id,
                    'docente_nombre' => optional($asignacion->docente)->nombre . ' ' . 
                                    optional($asignacion->docente)->apellido_p . ' ' . 
                                    optional($asignacion->docente)->apellido_m,
                    'grado_id' => $asignacion->grado_id,
                    'grado_nombre' => optional($asignacion->grado)->nombre,
                    'grupo_id' => $asignacion->grupo_id,
                    'grupo_nombre' => optional($asignacion->grupo)->nombre,
                ];
            });

        return Inertia::render('Salones/Index', [
            'asignaciones' => $asignaciones,
            'docente' => $docenteId ? Docente::find($docenteId) : null,
            'grados' => Grado::select('id', 'nombre')->get(),
            'grupos' => Grupo::select('id', 'nombre')->get()
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'docente_id' => 'required|exists:docentes,id',
            'grado_id' => 'required|exists:grados,id',
            'grupo_id' => 'required|exists:grupos,id'
        ]);

        $exists = AsignaDocente::where('docente_id', $validated['docente_id'])
            ->where('grado_id', $validated['grado_id'])
            ->where('grupo_id', $validated['grupo_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'message' => 'Esta asignación ya existe'
            ]);
        }

        AsignaDocente::create($validated);

        return redirect()->route('salones.index')->with('success', 'Asignación creada correctamente');
    }

    public function update(Request $request, $id)
    {
        $asignacion = AsignaDocente::findOrFail($id);

        $validated = $request->validate([
            'docente_id' => 'required|exists:docentes,id',
            'grado_id' => 'required|exists:grados,id',
            'grupo_id' => 'required|exists:grupos,id'
        ]);

        $exists = AsignaDocente::where('id', '!=', $id)
            ->where('docente_id', $validated['docente_id'])
            ->where('grado_id', $validated['grado_id'])
            ->where('grupo_id', $validated['grupo_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'message' => 'Esta asignación ya existe'
            ]);
        }

        $asignacion->update($validated);

        return redirect()->route('salones.index')->with('success', 'Asignación actualizada correctamente');
    }

    public function destroy($id)
    {
        $asignacion = AsignaDocente::findOrFail($id);
        $asignacion->delete();

        return redirect()->route('salones.index')->with('success', 'Asignación eliminada correctamente');
    }
}