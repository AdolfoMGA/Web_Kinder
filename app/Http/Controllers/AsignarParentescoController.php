<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AsignarParentescoController extends Controller
{
    public function verTutores($estudiante_id)
    {
        // Obtener los tutores relacionados al estudiante_id
        $tutores = DB::table('asignaparentescos')
            ->where('estudiante_id', $estudiante_id)
            ->get();

        // Retornar la vista de Inertia con los tutores
        return Inertia::render('Tutores/VerTutores', [
            'tutores' => $tutores
        ]);
    }
    public function destroy($id)
    {
        DB::table('asignaparentescos')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Tutor eliminado correctamente');
    }
    public function edit($id)
    {
        $tutor = DB::table('asignaparentescos')->where('id', $id)->first();

        return Inertia::render('Tutores/EditarTutor', [
            'tutor' => $tutor
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido_p' => 'required|string|max:255',
            'apellido_m' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
            'domicilio' => 'nullable|string|max:255',
            'foto' => 'nullable|image|max:2048', // si aceptas actualización de foto
        ]);

        $datos = $request->only(['nombre', 'apellido_p', 'apellido_m', 'telefono', 'correo', 'domicilio']);

        // Si se sube nueva foto
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('tutores', 'public');
            $datos['foto'] = $foto;
        }

        DB::table('asignaparentescos')->where('id', $id)->update($datos);

        return redirect()->route('estudiantes.index')->with('success', 'Tutor actualizado correctamente');
    }
}
