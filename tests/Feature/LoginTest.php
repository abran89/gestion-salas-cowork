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
     * Prueba básica para verificar la funcionalidad de inicio de sesión
     *
     * Esta prueba crea un usuario con datos aleatorios utilizando Faker
     * Posteriormente realiza una solicitud de inicio de sesión con las credenciales correctas
     * Verifica que la sesión se inicie correctamente y que la redirección sea a la página esperada
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
     * Esta prueba crea un usuario con datos aleatorios
     * Luego intenta hacer inicio de sesión con una contraseña incorrecta
     * Verifica que el usuario no sea autenticado y que se muestre el error correspondiente en la sesión
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
