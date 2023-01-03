<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Models\User;
use Tests\TestCase;

class GeneralTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    // CREACION DE PRUEBAS UNITARIAS POR ENDPOINT
    // Las APIs probadas se dividen para:
    // ⚪ General | 🟢 Admin | 🔵 Técnico | 🟣 Cliente

    // ⚪ 1. Iniciar sesión, cerrar sesión y recuperar contraseña
    // - Iniciar sesión
    public function test_inicio_de_sesion()
    {
        $test_request = $this->post('/api/v1/login', [
            "email" => "chance12@example.org",
            "password" => "happySad1*"
        ]);
        $test_request->assertStatus(200);
    }

    // ⚪ 2. Modificar perfil de usuario
    // - Modificar perfil
    public function test_modificacion_de_perfil()
    {
        $user = User::factory()->make(['role_id' => 2]);
        $profile = [
            "username" => "Juanca13",
            "first_name" => "Juan",
            "last_name" => "Carol",
            "cedula" => "1694698942",
            "email" => "juanca13@gmail.com",
            "birthdate" => "1999-08-15",
            "home_phone" => "0236379",
            "personal_phone" => "0992896598",
            "address" => "453 Guamani. 7"
        ];
        $test_request = $this->actingAs($user)->post('/api/v1/profile/', $profile);
        $test_request->assertStatus(200);
    }

    // -------------------------------------------------

    // 🟢 3. Gestión de solicitudes de afiliación
    // - Visualizar solicitudes de afiliacion atendidas
    public function test_visualizacion_de_solicitudes_de_afiliacion()
    {
        $user = User::where('id', 1)->first();
        $test_request = $this->actingAs($user)->get('/api/v1/manage/affiliations');
        $test_request->assertStatus(200);
    }

    // 🟢 4. Visualización de comentarios, sugerencias y calificación de los técnicos
    // - Visualizar comentarios de un tecnico
    public function test_visualizacion_de_comentarios_de_un_tecnico()
    {
        $user = User::where('id', 1)->first();
        $test_request = $this->actingAs($user)->get(sprintf('/api/v1/manage-tec/show/%u', 6));
        $test_request->assertStatus(200);
    }

    // -------------------------------------------------

    // 🔵 5. Registro de usuario para el perfil técnico
    // - Registrar usuario técnico
    public function test_resgistro_de_tecnico()
    {
        $test_request = $this->post('/api/v1/register-tecnico', [
            "username" => "Hector89",
            "first_name" => "Hector",
            "last_name" => "Perez",
            "personal_phone" => "0992006598",
            "address" => "453 Chillogallo Oval Apt. 7",

            "cedula" => "1622698942",
            "email" => "hector89@gmail.com",

            "birthdate" => "1999-08-15",
            "home_phone" => "0296379",

            "password" => "happySad1*",
            "password_confirmation" => "happySad1*"
        ]);
        $test_request->assertStatus(200);
    }

    // 🔵 6. Solicitación de afiliación
    // - Crear solicitar afiliacion
    public function test_crear_solicitud_de_afiliacion()
    {
        $user = User::where('id', 37)->first();
        $affiliation = [
            "profession" => "Ingeniero en sistemas",
            "specialization" => "Informatica computacional",
            "work_phone" => "0988659935",
            "attention_schedule" => "De 8 de la mañana hasta las 3 de la noche",
            "local_name" => "CompuLed",
            "local_address" => "67 chillogallo Oval Apt. 7",
            "confirmation" => true
        ];
        $test_request = $this->actingAs($user)->post('/api/v1/affiliation/create', $affiliation);
        $test_request->assertStatus(200);
    }

    // 🔵 7. Gestión de servicios
    // - Visualizar servicios generados
    public function test_visualizacion_de_servicios_generados()
    {
        $user = User::where('id', 7)->first();
        $test_request = $this->actingAs($user)->get('/api/v1/service');
        $test_request->assertStatus(200);
    }

    // 🔵 8. Aprobación de servicios
    // - Aprobar servicio
    public function test_aprobacion_de_servicio()
    {
        $user = User::where('id', 9)->first();
        $test_request = $this->actingAs($user)->get(sprintf('/api/v1/manage-hiring/approve/%u', 36));
        $test_request->assertStatus(200);
    }

    // 🔵 9. Visualización de comentarios, sugerencias y calificación de los servicios
    // - Visualizar comentarios ...
    public function test_visualizacion_de_comentarios()
    {
        $user = User::where('id', 7)->first();
        $test_request = $this->actingAs($user)->get('/api/v1/view-satisfaction-form');
        $test_request->assertStatus(200);
    }

    // -------------------------------------------------

    // 🟣 10. Iniciar sesión, cerrar sesión y recuperar contraseña para el cliente
    // - Iniciar sesión
    public function test_iniciar_sesion_para_clientes()
    {
        $test_request = $this->post('/api/v1/loginCli', [
            "email" => "leoni23@gmail.con",
            "password" => "happySad1*"
        ]);
        $test_request->assertStatus(200);
    }

    // 🟣 11. Registro de usuario para el perfil cliente
    // - Registrar usuario cliente
    public function test_resgistro_de_cliente()
    {
        $test_request = $this->post('/api/v1/register-cliente', [
            "username" => "Manu17",
            "first_name" => "Manu",
            "last_name" => "Auqui",
            "personal_phone" => "0992094598",
            "address" => "453 Chillogallo Oval Apt. 7",

            "cedula" => "1784698942",
            "email" => "manu17@gmail.com",

            "birthdate" => "1999-08-15",
            "home_phone" => "0266279",

            "password" => "happySad1*",
            "password_confirmation" => "happySad1*"
        ]);
        $test_request->assertStatus(200);
    }

    // 🟣 12. Visualización de servicios
    // - Visualizar servicios
    public function test_visualizacion_de_servicios()
    {
        $test_request = $this->get('/api/v1/view-service');
        $test_request->assertStatus(200);
    }

    // 🟣 13. Contratación de servicios
    // - Contratar servicio
    public function test_contratacion_de_servicio()
    {
        $user = User::where('id', 35)->first();
        $hiring = [
            "device" => "Computadora",
            "model" => "HP-500",
            "brand" => "HP",
            "serie" => "HSOE874N395",
            "description_problem" => "Necesito el formateo completo"
        ];
        $test_request = $this->actingAs($user)->post(sprintf('/api/v1/hiring/%u', 8), $hiring);
        $test_request->assertStatus(200);
    }

    // 🟣 14. Gestión de solicitudes de contratacion
    // - Visualizar contrataciones
    public function test_visualizacion_de_contrataciones()
    {
        $user = User::where('id', 35)->first();
        $test_request = $this->actingAs($user)->get('/api/v1/hiring/show');
        $test_request->assertStatus(200);
    }

    // 🟣 15. Comentar, sugerir y calificar los servicios
    // - Comentar ... un servicio
    public function test_comentar_un_servicio()
    {
        $user = User::where('id', 16)->first();
        $comment = [
            "comment" => "El trabaja resulto satisfactorio",
            "suggestion" => "Nada que sugerir, todo bien",
            "qualification" => 9.85
        ];
        $test_request = $this->actingAs($user)->post(sprintf('/api/v1/satisfaction-form/create/%u', 34), $comment);
        $test_request->assertStatus(200);
    }
}
