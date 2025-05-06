<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\GradosController;
use App\Http\Controllers\GruposController;
use App\Http\Controllers\DocentesController;
use App\Http\Controllers\RegistroEstudianteController;
use App\Http\Controllers\AsignarParentescoController;
use App\Http\Controllers\SalonController;
use App\Models\Rol;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FacialController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'canResetPassword' => Route::has('password.request'), // 👈 Asegúrate de incluir esto
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/inicio', function(){
        return Inertia::render('Inicio');
    })->name('inicio');
    
    Route::get('/registro_a', function(){
        return Inertia::render('Registro_alumnos');
    })->name('registro_a');

    Route::get('/grados', function(){
        return Inertia::render('grados');
    })->name('grados');

    Route::get('/grupos', function(){
        return Inertia::render('grupos');
    })->name('grupos');

    Route::get('/estudiantes', function(){
        return Inertia::render('estudiantes');
    })->name('estudiantes');

    Route::get('/docentes', function(){
        return Inertia::render('docentes');
    })->name('docentes');

    Route::get('/RegistrarEstudiante', function(){
        return Inertia::render('RegistrarEstudiante');
    })->name('RegistrarEstudiante');

});

Route::resource('grados',GradosController::class);
Route::post('/grados', [GradosController::class, 'store'])->name('grados.store');
Route::put('/grados/{grado}', [GradosController::class, 'update'])->name('grados.update');

Route::resource('grupos',GruposController::class);
Route::post('/grupos', [GruposController::class, 'store'])->name('grupos.store');
Route::put('/grupos/{grupo}', [GruposController::class, 'update'])->name('grupos.update');

Route::resource('estudiantes',EstudiantesController::class);
Route::post('/estudiantes', [EstudiantesController::class, 'store'])->name('estudiantes.store');
Route::put('/estudiantes/{estudiante}', [EstudiantesController::class, 'update'])->name('estudiantes.update');

Route::resource('docentes',DocentesController::class);
Route::post('/docentes', [DocentesController::class, 'store'])->name('docentes.store');
Route::put('/docentes/{docente}', [DocentesController::class, 'update'])->name('docentes.update');


Route::post('/asignar-docente', [DocentesController::class, 'asignarDocente'])->name('asignadocentes.store');

Route::post('/estudiantes/parentesco', [EstudiantesController::class, 'storeParentesco'])
     ->name('asignaparentescos.store'); 
     
Route::get('/roles', function () {
        return Rol::select('id', 'descripcion')->get();
    });
     
Route::get('/tutores/{estudiante}', [AsignarParentescoController::class, 'verTutores'])->name('tutores.byEstudiante');
Route::delete('/asignaparentescos/{id}', [AsignarParentescoController::class, 'destroy'])
    ->name('asignaparentescos.destroy');
Route::get('/asignaparentescos/{id}/edit', [AsignarParentescoController::class, 'edit'])->name('asignaparentescos.edit');
Route::put('/asignaparentescos/{id}', [AsignarParentescoController::class, 'update'])->name('asignaparentescos.update');

Route::middleware(['auth', 'verified'])->group(function () {
    // Ruta principal para docentes
    Route::get('/docente/registrar-estudiante', [RegistroEstudianteController::class, 'index'])
         ->name('docente.registrar.estudiante');
    
    // Ruta para filtrar estudiantes (ajustada para coincidir con tu componente)
    Route::get('/docente/estudiantes/{grado_id}/{grupo_id}', 
        [RegistroEstudianteController::class, 'getStudentsByGradeAndGroup']
    )->name('estudiantes.byGradeGroup');
});

Route::get('/estudiantes/grado/{grado}/grupo/{grupo}', [RegistroEstudianteController::class, 'getStudentsByGradeAndGroup'])
    ->name('estudiantes.byGradeGroup');

Route::get('/asignaciones', [DocentesController::class, 'index'])->name('asignaciones.index');

Route::resource('salones', \App\Http\Controllers\SalonController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->names([
        'index' => 'salones.index',
        'store' => 'salones.store',
        'update' => 'salones.update',
        'destroy' => 'salones.destroy'
    ]);

    Route::middleware('auth')->group(function () {
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{user}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    });

Route::post('/facial/run', [FacialController::class, 'run'])->name('facial.run');
