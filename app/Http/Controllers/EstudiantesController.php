<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Grado;
use App\Models\Grupo;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DB;

class EstudiantesController extends Controller
{
    public function index()
    {
        // Obtener los estudiantes con las relaciones necesarias
        $estudiantes = Estudiante::select('estudiantes.id', 'estudiantes.nombre', 'apellido_paterno', 'apellido_materno', 'edad',
            'grado_id', 'grados.nombre as grado', 'grupo_id', 'grupos.nombre as grupo', 'docente_id', 'docentes.nombre as docente')
            ->join('grados', 'grados.id', '=', 'estudiantes.grado_id')
            ->join('grupos', 'grupos.id', '=', 'estudiantes.grupo_id')
            ->join('docentes', 'docentes.id', '=', 'estudiantes.docente_id')
            ->paginate(10);

        // Obtener los grados, grupos y docentes
        $grados = Grado::all();
        $grupos = Grupo::all();
        $docentes = Docente::all();

        // Pasar a la vista los estudiantes, grados, grupos y docentes
        return Inertia::render('Estudiantes/Index', [
            'estudiantes' => $estudiantes,
            'grados' => $grados,
            'grupos' => $grupos,
            'docentes' => $docentes
        ]);
    }
    public function create()
    {
       //
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'apellido_paterno' => 'required|max:100',
            'apellido_materno' => 'required|max:100',
            'edad' => 'required|numeric',
            'grado_id' => 'required|numeric',
            'grupo_id' => 'required|numeric',
            'docente_id' => 'required|numeric'
        ]);
        
        $estudiante = new Estudiante($request->input());
        $estudiante->save();
        
        return redirect('estudiantes');
    }


    public function edit(Estudiante $estudiante)
    {
        ////
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'apellido_paterno' => 'required|max:100',
            'apellido_materno' => 'required|max:100',
            'edad' => 'required|numeric',
            'grado_id' => 'required|numeric',
            'grupo_id' => 'required|numeric',
            'docente_id' => 'required|numeric'
        ]);
        $estudiante->update($request->input());
        return redirect('estudiantes');
    }

    public function destroy(Estudiante $estudiante)
    {
        $estudiante->delete();
        return redirect('estudiantes');
    }
}