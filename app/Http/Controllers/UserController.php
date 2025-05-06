<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rol;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::with('rol:id,descripcion')
            ->select(['id', 'name', 'ap', 'am', 'email', 'rol_id', 'created_at'])
            ->orderBy('name')
            ->paginate(10);

        return Inertia::render('Usuarios/Index', [
            'users' => $users,
            'roles' => Rol::select(['id', 'descripcion'])->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Usuarios/Create', [
            'roles' => Rol::select(['id', 'descripcion'])->get()
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'ap' => 'required|string|max:255',
            'am' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'rol_id' => 'required|exists:roles,id'
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        return Inertia::render('Usuarios/Edit', [
            'user' => $user->only(['id', 'name', 'ap', 'am', 'email', 'rol_id']),
            'roles' => Rol::select(['id', 'descripcion'])->get()
        ]);
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'ap' => 'required|string|max:255',
            'am' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'rol_id' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        $data = $request->validate($rules);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}