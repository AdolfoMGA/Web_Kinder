<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Grado;
use App\Models\Grupo;
use App\Models\Asignadocente;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegistroEstudianteController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'No autorizado');
        }

        // Para administradores
        if ($user->rol->descripcion === 'Administrador') {
            return Inertia::render('Docentes/RegistrarEstudiante', [
                'grados' => Grado::all(['id', 'nombre']),
                'grupos' => Grupo::all(['id', 'nombre']),
                'estudiantesFiltrados' => [],
                'filtros' => null,
                'isAdmin' => true
            ]);
        }

        // Para docentes
        if ($user->rol->descripcion === 'Docente') {
            // Verificar si tiene perfil de docente
            if (!$user->docente) {
                return redirect()->back()->with('error', 'No tienes un perfil de docente asociado');
            }

            // Obtenemos las asignaciones a través de la relación
            $asignaciones = $user->docente->asignaciones()
                ->with(['grado', 'grupo'])
                ->get();

            $grados = $asignaciones->map(fn($asign) => $asign->grado)->unique('id')->values();
            $grupos = $asignaciones->map(fn($asign) => $asign->grupo)->unique('id')->values();

            return Inertia::render('Docentes/RegistrarEstudiante', [
                'grados' => $grados,
                'grupos' => $grupos,
                'estudiantesFiltrados' => [],
                'filtros' => null,
                'isAdmin' => false
            ]);
        }

        abort(403, 'No tienes permiso para acceder a esta página.');
    }

    public function getStudentsByGradeAndGroup($grado, $grupo)
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'No autorizado');
        }

        // Verificar que existan el grado y grupo
        $gradoModel = Grado::findOrFail($grado);
        $grupoModel = Grupo::findOrFail($grupo);

        // Si es docente, verificar que esté asignado a este grupo
        if ($user->rol->descripcion === 'Docente') {
            $asignado = $user->docente->asignaciones()
                ->where('grado_id', $grado)
                ->where('grupo_id', $grupo)
                ->exists();

            if (!$asignado) {
                abort(403, 'No estás asignado a este grupo');
            }
        }

        // Obtener estudiantes del grado/grupo
        $estudiantes = Estudiante::with(['grado', 'grupo'])
            ->where('grado_id', $grado)
            ->where('grupo_id', $grupo)
            ->select(['id', 'nombre', 'apellido_paterno', 'apellido_materno', 'edad'])
            ->orderBy('nombre')
            ->orderBy('apellido_paterno')
            ->orderBy('apellido_materno')
            ->get();

        // Determinar qué grados y grupos mostrar según el rol
        if ($user->rol->descripcion === 'Administrador') {
            $grados = Grado::all(['id', 'nombre']);
            $grupos = Grupo::all(['id', 'nombre']);
        } else {
            $asignaciones = $user->docente->asignaciones()->with(['grado', 'grupo'])->get();
            $grados = $asignaciones->map(fn($a) => $a->grado)->unique('id')->values();
            $grupos = $asignaciones->map(fn($a) => $a->grupo)->unique('id')->values();
        }

        return Inertia::render('Docentes/RegistrarEstudiante', [
            'grados' => $grados,
            'grupos' => $grupos,
            'estudiantesFiltrados' => $estudiantes,
            'filtros' => [
                'grado_id' => $grado,
                'grupo_id' => $grupo,
                'grado_nombre' => $gradoModel->nombre,
                'grupo_nombre' => $grupoModel->nombre
            ],
            'isAdmin' => $user->rol->descripcion === 'Administrador'
        ]);
    }

    public function verTutoresPorEstudiante($estudiante_id)
    {
        $user = auth()->user();
    
        if (!$user) {
            abort(403, 'No autorizado');
        }
    
        $estudiante = Estudiante::findOrFail($estudiante_id);
    
        if ($user->rol->descripcion === 'Docente') {
            $asignado = $user->docente->asignaciones()
                ->where('grado_id', $estudiante->grado_id)
                ->where('grupo_id', $estudiante->grupo_id)
                ->exists();
    
            if (!$asignado) {
                abort(403, 'No tienes permiso para ver este estudiante');
            }
        }
    
        $tutores = Asignaparentesco::where('estudiante_id', $estudiante_id)->get();
    
        return Inertia::render('Tutores/TutoresEstudiante', [
            'tutores' => $tutores,
            'rol' => strtolower($user->rol->descripcion), // <-- asegúrate que esto está presente
            'estudiante' => [
                'nombre' => $estudiante->nombre,
                'apellido_paterno' => $estudiante->apellido_paterno,
                'apellido_materno' => $estudiante->apellido_materno,
            ],
        ]);
    }
    
    
}