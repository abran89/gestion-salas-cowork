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
     * Procesa el registro de un nuevo usuario, valida los datos del formulario,
     * crea un nuevo usuario y redirige al usuario con un mensaje de éxito
     *
     * Este método realiza lo siguiente:
     * - Valida los datos del formulario (nombre, correo electrónico y contraseña)
     * - Si los datos son inválidos, redirige de vuelta con los errores y los datos ingresados
     * - Si los datos son válidos, crea un nuevo usuario en la base de datos con la contraseña cifrada
     * - Redirige al usuario a la página principal con un mensaje de éxito
     *
     * @param Request $request Datos del formulario de registro
     * @return \Illuminate\Http\RedirectResponse
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

        return redirect('/')->with('success', 'Usuario creado correctamente.');
    }
}
