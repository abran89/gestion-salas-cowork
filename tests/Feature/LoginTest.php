<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;


class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Prueba para verificar que un usuario pueda iniciar sesión con credenciales correctas
     *
     * Este método realiza lo siguiente:
     * - Crea un usuario con una dirección de correo electrónico única y una contraseña segura
     * - Intenta iniciar sesión usando las credenciales del usuario
     * - Verifica que el usuario esté autenticado correctamente
     * - Verifica que el usuario sea redirigido a la página de inicio ("/home") después de iniciar sesión
     *
     * @return void
     */
    public function test_user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect('/home');
    }

   /**
     * Prueba para verificar que el inicio de sesión falla con credenciales incorrectas
     *
     * Este método realiza lo siguiente:
     * - Crea un usuario con una dirección de correo electrónico única y una contraseña segura
     * - Intenta iniciar sesión utilizando las credenciales del usuario con una contraseña incorrecta
     * - Verifica que el usuario no esté autenticado
     * - Verifica que se muestre un error de sesión relacionado con el campo "email"
     *
     * @return void
     */
    public function test_login_fails_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();

        $response->assertSessionHasErrors('email');
    }
}
