<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocentesController extends Controller
{
    public function index()
    {
        // Obtener los docentes
        $docentes = Docente::select('id', 'nombre', 'apellido_p', 'apellido_m')
            ->paginate(10);

        // Pasar a la vista los docentes
        return Inertia::render('Docentes/Index', [
            'docentes' => $docentes
        ]);
    }

    public function create()
    {
        // Aquí puedes devolver la vista para crear un nuevo docente, si es necesario
    }

    public function store(Request $request)
    {
        // Validación para el almacenamiento de un docente
        $request->validate([
            'nombre' => 'required|max:100',
            'apellido_p' => 'required|max:100',
            'apellido_m' => 'required|max:100',
        ]);
        
        // Crear un nuevo docente y guardarlo
        $docente = new Docente($request->input());
        $docente->save();
        
        // Redirigir al listado de docentes
        return redirect('docentes');
    }

    public function edit(Docente $docente)
    {
        // Aquí puedes devolver la vista para editar un docente, si es necesario
    }

    public function update(Request $request, Docente $docente)
    {
        // Validación para actualizar un docente
        $request->validate([
            'nombre' => 'required|max:100',
            'apellido_p' => 'required|max:100',
            'apellido_m' => 'required|max:100',
        ]);
        
        // Actualizar los datos del docente
        $docente->update($request->input());

        // Redirigir al listado de docentes
        return redirect('docentes');
    }

    public function destroy(Docente $docente)
    {
        // Eliminar el docente
        $docente->delete();

        // Redirigir al listado de docentes
        return redirect('docentes');
    }
}
