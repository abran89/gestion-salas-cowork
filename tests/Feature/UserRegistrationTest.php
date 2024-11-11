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
     * Prueba para verificar que un nuevo usuario se registre correctamente
     *
     * Este método realiza lo siguiente:
     * - Crea un conjunto de datos para un nuevo usuario, incluyendo nombre, correo electrónico y contraseña
     * - Simula el envío de una solicitud de registro con esos datos
     * - Verifica que el usuario haya sido guardado correctamente en la base de datos
     * - Verifica que el sistema redirija al usuario a la página principal después del registro
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
     * Prueba para verificar que el correo electrónico sea único durante el registro
     *
     * Este método realiza lo siguiente:
     * - Crea un usuario con un correo electrónico único previamente generado
     * - Intenta registrar un nuevo usuario utilizando el mismo correo electrónico que ya existe en la base de datos
     * - Verifica que solo haya un usuario registrado en la base de datos
     * - Verifica que se muestre un error en el correo electrónico durante el registro
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
     * Prueba para verificar que la confirmación de la contraseña sea válida durante el registro
     *
     * Este método realiza lo siguiente:
     * - Crea un conjunto de datos para registrar un nuevo usuario, donde la contraseña y su confirmación no coinciden
     * - Intenta registrar un usuario con las contraseñas no coincidentes
     * - Verifica que ningún usuario se haya registrado en la base de datos
     * - Verifica que se muestre un error en el campo de la contraseña
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
