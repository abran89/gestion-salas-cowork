# Gestion de reservas de salas de coworking

Este es un sistema de gestión de reservas de salas de coworking, **desarrollado utilizando el enfoque de Desarrollo Guiado por Pruebas (TDD)** con **Laravel 10** y **MySQL**. Desde el inicio, el sistema ha sido creado y validado a través de pruebas automatizadas, lo que garantiza que todas las funcionalidades estén correctamente implementadas y cumplan con los requisitos esperados.

## Enfoque de Desarrollo

El proyecto sigue una metodología **TDD (Test Driven Development)**, lo que significa que las funcionalidades fueron diseñadas e implementadas a partir de las pruebas. Las pruebas aseguran que el sistema funcione de acuerdo con los criterios de negocio y que se mantenga estable a medida que evoluciona. Todo el código se prueba exhaustivamente antes de ser integrado a la aplicación.

Durante el proceso de desarrollo, se ha utilizado **PHP Unit** y **Laravel's built-in testing tools** para implementar pruebas de integración, cubriendo escenarios como la gestión de usuarios, reservas de salas, y control de acceso basado en roles.

## Documentación del Código

Este proyecto está documentado utilizando **PHPDoc**, un estándar de documentación en PHP que permite generar documentación legible y útil para los desarrolladores. Los comentarios PHPDoc proporcionan descripciones detalladas sobre las funciones, métodos y clases, lo que facilita el mantenimiento y la comprensión del código.

### ¿Cómo se utiliza PHPDoc en el proyecto?

- **Métodos**: Cada método tiene un comentario PHPDoc detallado que describe su funcionalidad, los parámetros que recibe y el valor que retorna.

### Pruebas Implementadas

Las pruebas se implementaron antes de la implementación de las características y cubren los siguientes aspectos principales:

- **Acceso a rutas de administración**: Verifica que solo los administradores puedan acceder a las rutas de administración.
- **Inicio de sesión de usuarios**: Verifica que los usuarios puedan iniciar sesión con credenciales correctas y que las credenciales incorrectas sean rechazadas.
- **Gestión de reservas**: Asegura que los usuarios puedan crear reservas solo para salas disponibles, y que los administradores puedan ver y modificar todas las reservas.
- **Gestión de salas**: Verifica que solo los administradores puedan crear, editar y eliminar salas.

## Instalacion

Siga estos pasos para configurar y ejecutar la aplicación:

### Prerequisitos

- **PHP 8.1+**
- **Composer**
- **MySQL 8.1+**

### Pasos de Instalación
1. **Clone el repositorio**:

   ```bash
   git clone https://github.com/abran89/gestion-salas-cowork.git
   ```

2. **Instale las dependencias de PHP: Navege al directorio del proyecto y ejecute:**

    ```bash
   cd gestion-salas-cowork
   composer install
   ```

3. **Configure el archivo .env: Copie el archivo .env.example a .env:**

    ```bash
   cp .env.example .env
   ```

    **asegúrese de configurar las credenciales de la base de datos en el archivo .env:**

    ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nombre_de_la_base_de_datos
    DB_USERNAME=tu_usuario
    DB_PASSWORD=tu_contraseña
    ```
4. **Genere la clave de la aplicación:**

    ```bash
    php artisan key:generate
    ```
5. **Migraciones y seeding de la base de datos:**  Ejecute las migraciones para crear las tablas necesarias en la base de datos y también se ejecutará el seeder que crea un usuario administrador:

    ```bash
    php artisan migrate --seed
    ```

    **Credenciales del usuario administrador**

    - **nombre:** Admin
    - **Correo electrónico:** admin@admin.cl
    - **Contraseña:** adminpassword

6. **Inicie el servidor:** Si desea ejecutar el servidor de desarrollo, puedes usar el siguiente comando

    ```bash
    php artisan serve
    ```
    Ahora puedes acceder a la aplicación en http://127.0.0.1:8000.

## Ejecutar las Pruebas
El proyecto utiliza **PHPUnit** para las pruebas. Para ejecutar todas las pruebas, simplemente ejecuta el siguiente comando:

```bash
php artisan test
```

Esto ejecutará las pruebas en el directorio `tests/Feature` y `tests/Unit` y te proporcionará un resumen de los resultados.

### Pruebas Disponibles

**Las pruebas cubren los siguientes aspectos clave:**

- **LoginTest:** Verifica que los usuarios puedan iniciar sesión correctamente y que se manejen correctamente los errores de inicio de sesión.
- **ReservationTest:** Asegura que los usuarios puedan crear reservas, que no puedan hacer reservas para salas ocupadas, y que los administradores puedan gestionar las reservas.
- **RoomCrudTest:** Verifica que solo los administradores puedan crear, editar y eliminar salas.
- **UserRegistrationTest:** Asegura que los nuevos usuarios puedan registrarse correctamente, que se valide el correo electrónico y la confirmación de la contraseña.

### Resultados de las pruebas

Todas las pruebas se ejecutaron correctamente, lo que indica que el sistema cumple con los requisitos establecidos. A continuación se muestra el resumen de los resultados de las pruebas:

```bash
PASS  Tests\Feature\AdminMiddlewareTest
  ✓ only admins can access admin routes                                                                          0.52s

PASS  Tests\Feature\LoginTest
  ✓ user can login with correct credentials                                                                      0.07s
  ✓ login fails with invalid credentials                                                                         0.27s

PASS  Tests\Feature\ReservationTest
  ✓ user can create a reservation                                                                                0.12s
  ✓ user cannot create a reservation for an occupied room                                                        0.12s
  ✓ admin user sees all reservations                                                                             0.10s
  ✓ normal user sees only own reservations                                                                       0.10s
  ✓ admin can change reservation status                                                                          0.10s
  ✓ regular user cannot change reservation status                                                                0.10s

PASS  Tests\Feature\RoomCrudTest
  ✓ only admins can create rooms                                                                                 0.13s
  ✓ only admins can edit rooms                                                                                   0.11s
  ✓ only admins can delete rooms                                                                                 0.11s

PASS  Tests\Feature\UserRegistrationTest
  ✓ registers a new user successfully                                                                            0.11s
  ✓ validates the email is unique                                                                                0.08s
  ✓ validates the password confirmation
```

## Manejo Personalizado de Errores

### Descripción

Se ha implementado un manejo personalizado de errores, específicamente para gestionar excepciones relacionadas con la conexión a la base de datos. En caso de que el sistema no pueda conectarse a la base de datos o se presenten otros errores relacionados, el usuario recibirá un mensaje claro y comprensible, sin exponer detalles técnicos sensibles.

### Implementación

El sistema detecta automáticamente los errores de conexión a la base de datos, como los problemas con MySQL, y maneja las excepciones de manera controlada. Cuando ocurre un error, el sistema:

- Captura la excepción relacionada con la base de datos.
- Muestra un mensaje amigable al usuario, indicando que ha habido un problema con la conexión.
- Proporciona un mensaje de error general y se asegura de que los detalles específicos no se expongan al usuario final.

### Ventajas

- **Mejor experiencia de usuario**: En lugar de mostrar errores técnicos o mensajes crípticos, el usuario recibe un mensaje claro y comprensible, mejorando la experiencia del usuario final.
  
- **Seguridad**: No se exponen detalles sensibles sobre la infraestructura del sistema ni de la base de datos, lo que reduce el riesgo de posibles vulnerabilidades.
  
- **Diagnóstico simplificado**: El manejo adecuado de los errores permite a los administradores y desarrolladores identificar y resolver problemas con mayor rapidez, sin comprometer la seguridad o la experiencia del usuario.
  
- **Mantenimiento y escalabilidad**: Esta estrategia asegura que el sistema esté preparado para manejar problemas de conexión de forma uniforme y controlada, lo que facilita la escalabilidad y mantenimiento del sistema a largo plazo.

## Características

### funcionalidades principales

- **Gestión de usuarios**

    - Registro de nuevos usuarios con validación de email único y confirmación de contraseña.
    - Inicio de sesión de usuarios con credenciales válidas y manejo de fallos de autenticación.

- **Gestión de reservas:**

    - Los usuarios pueden crear reservas para salas disponibles.
    - Se valida que no se puedan hacer reservas para salas ya ocupadas.
    - Los usuarios solo pueden ver y gestionar sus propias reservas, mientras que los administradores pueden ver todas las reservas.

- **Gestión de salas:**

    - Solo los administradores pueden crear, editar y eliminar salas.

- **Control de acceso basado en roles:**

    - Rutas de administración protegidas solo para usuarios con el rol de administrador.

## Agradecimientos

Quiero expresar mi agradecimiento por la oportunidad de participar en este proyecto.

Si no soy elegido para el puesto, agradecería mucho si pudieran enviarme comentarios sobre mi desempeño. La retroalimentación es clave para seguir mejorando y perfeccionando mis habilidades. Estoy siempre dispuesto a aprender y crecer, y cualquier sugerencia será muy valiosa para mi desarrollo profesional.

¡Gracias nuevamente por esta oportunidad!
