<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EstudiantesController;
use App\Http\Controllers\GradosController;
use App\Http\Controllers\GruposController;

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