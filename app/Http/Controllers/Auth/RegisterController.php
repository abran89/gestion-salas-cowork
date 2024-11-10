<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{

    /**
     * Maneja la solicitud de registro de un nuevo usuario
     * Valida los datos recibidos, crea un nuevo usuario en la base de datos
     * y redirige al usuario a la página de inicio si el registro es exitoso
     *
     * @param Request $request La solicitud HTTP que contiene los datos de registro del usuario
     * @return \Illuminate\Http\RedirectResponse Redirección al inicio o respuesta con errores de validación
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false,
        ]);

        return redirect('/');
    }
}
