<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GruposController extends Controller
{
    public function index()
    {
        $grupos = Grupo::all(); 
        return Inertia::render('Grupos/Index', ['grupos' => $grupos]);
    }

    public function create()
    {
        return Inertia::render('Grupos/Create'); 
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|max:100']);
        $grupo = new Grupo($request->input());
        $grupo->save();
        return redirect('grupos');
    }

    public function edit(Grupo $grupo)
    {
        return Inertia::render('Grupos/Edit', ['grupo' => $grupo]);
    }

    public function update(Request $request, Grupo $grupo)
    {
        $request->validate(['nombre' => 'required|max:100']);
        $grupo->update($request->all());
        return redirect('grupos');
    }

    public function destroy(Grupo $grupo)
    {
        $grupo->delete();
        return redirect('grupos');
    }
}
