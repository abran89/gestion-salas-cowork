<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Prueba que verifica si un usuario puede registrarse correctamente.
     * Simula el envío del formulario de registro y comprueba
     * que el usuario se crea en la base de datos y es redirigido
     *
     * @return void
     */
    public function test_registers_a_new_user_successfully()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $data);

        $this->assertDatabaseHas('users', [
            'email' => $data['email'],
        ]);

        $response->assertRedirect('/');
    }

   /**
     * Prueba que valida que no se puedan registrar usuarios con correos electrónicos duplicados
     * Crea un usuario y luego intenta registrar otro usuario con el mismo correo,
     * verificando que se genere un error de validación
     *
     * @return void
     */
    public function test_validates_the_email_is_unique()
    {
        $existingEmail = $this->faker->unique()->safeEmail;

        User::factory()->create(['email' => $existingEmail]);

        $data = [
            'name' => $this->faker->name,
            'email' => $existingEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $data);

        $this->assertDatabaseCount('users', 1);

        $response->assertSessionHasErrors('email');
    }

     /**
     * Prueba que verifica que la confirmación de la contraseña sea correcta
     * Intenta registrar un usuario con una contraseña y confirmación que no coinciden
     * y comprueba que no se crea el usuario
     *
     * @return void
     */
    public function test_validates_the_password_confirmation()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'password_confirmation' => 'password1234',
        ];

        $response = $this->post('/register', $data);

        $this->assertDatabaseCount('users', 0);

        $response->assertSessionHasErrors('password');
    }
}
