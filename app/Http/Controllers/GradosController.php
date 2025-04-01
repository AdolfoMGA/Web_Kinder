<?php

namespace App\Http\Controllers;

use App\Models\Grado;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GradosController extends Controller
{
    public function index()
    {
        $grados = Grado::all(); 
        return Inertia::render('Grados/Index', ['grados' => $grados]);
    }

    public function create()
    {
        return Inertia::render('Grados/Create'); 
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|max:100']);
        $grado = new Grado($request->input());
        $grado->save();
        return redirect('grados');
    }

    public function edit(Grado $grado)
    {
        return Inertia::render('Grados/Edit', ['grados' => $grado]);
    }

    public function update(Request $request, Grado $grado)
    {
        $request->validate(['nombre' => 'required|max:100']);
        $grado->update($request->all());
        return redirect('grados');
    }

    public function destroy(Grado $grado)
    {
        $grado->delete();
        return redirect('grados');
    }
}
