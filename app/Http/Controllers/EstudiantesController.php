<?php

namespace App\Http\Controllers;

use App\Models\Grado;
use App\Models\Grupo;
use App\Models\Estudiante;
use App\Models\Parentesco;
use App\Models\Asignaparentesco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EstudiantesController extends Controller
{
    public function index()
    {
        $estudiantes = Estudiante::select(
            'estudiantes.id', 'estudiantes.nombre', 'apellido_paterno', 'apellido_materno', 'edad',
            'grado_id', 'grados.nombre as grado', 'grupo_id', 'grupos.nombre as grupo'
        )
            ->join('grados', 'grados.id', '=', 'estudiantes.grado_id')
            ->join('grupos', 'grupos.id', '=', 'estudiantes.grupo_id')
            ->paginate(10);

        $grados = Grado::all();
        $grupos = Grupo::all();
        
        // Modifica esta parte para el formato que necesita SelectInput
        $parentescos = Parentesco::select('id as value', 'descripcion as label')->get();

        return Inertia::render('Estudiantes/Index', [
            'estudiantes' => $estudiantes,
            'grados' => $grados,
            'grupos' => $grupos,
            'parentescos' => $parentescos
        ]);
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
        ]);

        $estudiante = new Estudiante($request->input());
        $estudiante->save();

        return redirect('estudiantes');
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
        ]);
        $estudiante->update($request->input());
        return redirect('estudiantes');
    }

    public function destroy(Estudiante $estudiante)
    {
        $estudiante->delete();
        return redirect('estudiantes');
    }
    public function storeParentesco(Request $request)
    {
        $validated = $request->validate([
            'estudiante_id' => 'required|exists:estudiantes,id',
            'parentesco_id' => 'required|exists:parentescos,id',
            'nombre' => 'required|string|max:100',
            'apellido_p' => 'required|string|max:100',
            'apellido_m' => 'required|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'domicilio' => 'nullable|string|max:255',
            'correo' => 'nullable|email|max:100',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $estudiante = Estudiante::find($validated['estudiante_id']);
        $parentesco = Parentesco::find($validated['parentesco_id']);

        // Construir la carpeta base y el nombre del archivo
        $carpeta = "parentescos/{$estudiante->nombre} {$estudiante->apellido_p} {$estudiante->apellido_m} ({$estudiante->id})";
        $nombreImagen = "{$validated['nombre']} {$validated['apellido_p']} {$validated['apellido_m']} ({$parentesco->descripcion}).png";

        // Asegurar que la carpeta existe (se crea solo si no existe)
        if (!Storage::disk('public')->exists($carpeta)) {
            Storage::disk('public')->makeDirectory($carpeta);
        }

        // Procesar y guardar la foto
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->storeAs($carpeta, $nombreImagen, 'public');
            $validated['foto'] = $path;
        }

        // Crear el registro
        Asignaparentesco::create($validated);

        return redirect('estudiantes');
    }

    public function show($id)
    {
        abort(404); // O simplemente no implementarlo
    }

}