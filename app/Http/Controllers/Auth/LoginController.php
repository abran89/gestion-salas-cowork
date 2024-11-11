<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Procesa el inicio de sesión del usuario, valida los datos del formulario de inicio de sesión,
     * autentica al usuario y redirige a la página deseada
     *
     * Este método realiza lo siguiente:
     * - Valida los datos del formulario (correo electrónico y contraseña)
     * - Si los datos son incorrectos, redirige con los errores de validación
     * - Si la autenticación es exitosa, redirige al dashboard
     * - Si la autenticación falla, lanza una excepción con un mensaje de error
     *
     * @param Request $request Datos del formulario de inicio de sesión
     * @return \Illuminate\Http\RedirectResponse Redirige al dashboard
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->intended('/home');
        }

        throw ValidationException::withMessages([
            'email' => ['Las credenciales proporcionadas no son correctas.'],
        ]);
    }

    /**
     * Procesa el cierre de sesión del usuario, invalida la sesión y redirige al usuario a la página de inicio
     *
     * Este método realiza lo siguiente:
     * - Cierra la sesión del usuario utilizando el método `Auth::logout()`
     * - Invalida la sesión actual para evitar posibles usos no autorizados
     * - Regenera el token de la sesión para prevenir ataques CSRF
     * - Redirige al usuario a la página de inicio ('/')
     *
     * @param Request $request Datos de la sesión
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
