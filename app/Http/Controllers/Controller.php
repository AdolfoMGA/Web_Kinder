<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        Inertia::share('auth.user', function () {
            $user = auth()->user();

            if (!$user) {
                return null;
            }

            return [
                'id' => $user->id,
                'name' => $user->name,
                'rol_id' => $user->rol_id,
                'rol' => $user->rol->descripcion ?? null,
                'email' => $user->email,
            ];
        });
    }
}
