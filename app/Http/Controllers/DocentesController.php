<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Grupo;
use App\Models\Grado;
use App\Models\Asignadocente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocentesController extends Controller
{
    public function index()
    {
        $docentes = Docente::select('id', 'nombre', 'apellido_p', 'apellido_m')->paginate(10);
        $grupos = Grupo::select('id', 'nombre')->get();
        $grados = Grado::select('id','nombre')->get();

        return Inertia::render('Docentes/Index', [
            'docentes' => $docentes,
            'grupos' => $grupos,
            'grados' => $grados
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'apellido_p' => 'required|max:100',
            'apellido_m' => 'required|max:100',
        ]);

        Docente::create($request->only('nombre', 'apellido_p', 'apellido_m'));

        return redirect('docentes');
    }

    public function update(Request $request, Docente $docente)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'apellido_p' => 'required|max:100',
            'apellido_m' => 'required|max:100',
        ]);

        $docente->update($request->only('nombre', 'apellido_p', 'apellido_m'));

        return redirect('docentes');
    }

    public function destroy(Docente $docente)
    {
        $docente->delete();

        return redirect('docentes');
    }

    public function asignarDocente(Request $request)
    {
        $request->validate([
            'grado_id' => 'required|exists:grados,id',
            'grupo_id' => 'required|exists:grupos,id',
            'docente_id' => 'required|exists:docentes,id',
        ]);
    
        Asignadocente::updateOrCreate(
            [
                'docente_id' => $request->docente_id,
                'grupo_id' => $request->grupo_id,
                'grado_id' => $request->grado_id,
            ],
        );
    
        return redirect('docentes');
    }
    
}
