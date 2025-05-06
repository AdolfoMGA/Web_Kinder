<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Docente;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'ap' => ['required', 'string', 'max:255'],
            'am' => ['required', 'string', 'max:255'],
            'rol' => ['required', 'exists:roles,id'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        // Creamos al usuario
        $user = User::create([
            'name' => $input['name'],
            'ap' => $input['ap'],
            'am' => $input['am'],
            'rol_id' => $input['rol'], 
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        // Si es docente, lo agregamos también a la tabla docentes
        if ($user->rol_id == 2) { // Asegúrate que el 2 es el rol de "docente"
            Docente::create([
                'nombre' => $user->name,
                'apellido_p' => $user->ap,
                'apellido_m' => $user->am,
                'user_id' => $user->id,
            ]);
        }

        return $user;
    }
}
