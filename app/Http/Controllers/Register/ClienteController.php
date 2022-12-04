<?php

namespace App\Http\Controllers\Register;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Notifications\ClienteRegistroNotifi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordValidator;

class ClienteController extends Controller
{
    // Crear un nuevo usuario cliente
    public function register(Request $request)
    {
        // Validar que el usuario sea mayor de edad
        $allowed_date_range = [
            'max' => date('Y-m-d', strtotime('-90 years')),
            'min' => date('Y-m-d', strtotime('-16 years')),
        ];

        // Validación de los datos de entrada
        $request->validate([
            'first_name' => ['required', 'string', 'min:3', 'max:35'],
            'last_name' => ['required', 'string', 'min:3', 'max:35'],
            'personal_phone' => ['required', 'numeric', 'digits:10'],
            'address' => ['required', 'string', 'min:5', 'max:50'],


            'cedula' => ['nullable', 'numeric', 'digits:10', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'min:5', 'max:20', 'unique:users'],

            'home_phone' => ['nullable', 'numeric', 'digits:7'],
            'birthdate' => [
                'nullable', 'string', 'date_format:Y-m-d',
                "after_or_equal:{$allowed_date_range['max']}",
                "before_or_equal:{$allowed_date_range['min']}"
            ],
        ]);

        // Validación de los datos de entrada
        $validated = $request->validate([
            'password' => [
                'required', 'string', 'confirmed',
                PasswordValidator::defaults()->mixedCase()->numbers()->symbols()
            ]
        ]);

        // Obtiene el rol del usuario Tecncio
        $role = Role::where('slug', 'cliente')->first();

        // Crear una instancia del usuario
        $user = new User($request->all());

        // Se setea el paasword al usuario
        $user->password = Hash::make($validated['password']);

        // Se almacena el usuario en la BD
        $role->users()->save($user);

        // Se procede a invocar la función para en envío de una notificación de registro
        $this->sendNotifications($user);

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Successfully registered user');
    }

    // Función para enviar notificaciones para el usuario registrado
    private function sendNotifications(User $user)
    {
        // https://laravel.com/docs/9.x/notifications#sending-notifications
        $user->notify(
            new ClienteRegistroNotifi(
                user_name: $user->getFullName(),
                role_name: $user->role->name
            )
        );
    }
}
