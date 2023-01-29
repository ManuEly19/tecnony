<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Models\User;
use Tests\TestCase;

class S3ClienteTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    // CREACION DE PRUEBAS UNITARIAS POR ENDPOINT
    // Las APIs probadas se dividen para:
    // ⚪ General | 🟢 Admin | 🔵 Técnico | 🟣 Cliente

    // 🟣 Inicio de sesión, cierre de sesión y recuperación de contraseña para el perfil cliente.
    // - [ ]  Login para clientes.
    public function test_iniciar_sesion_para_clientes()
    {
        $test_request = $this->post('/api/v1/loginCli', [
            "email" => "leoni23@gmail.con",
            "password" => "happySad1*"
        ]);
        $test_request->assertStatus(200);
    }

    // 🟣 Registro de usuario para el perfil cliente.
    // - [ ]  Registro de usuario cliente.
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

    // 🟣 Visualización de servicios.
    // - [ ]  Visualización de servicios.
    public function test_visualizacion_de_servicios()
    {
        $test_request = $this->get('/api/v1/view-service');
        $test_request->assertStatus(200);
    }

    // 🟣 Contratación de servicios.
    // - [ ]  Contratación de servicio.
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

    // 🟣 Gestión de solicitudes de contratación.
    // - [ ]  Visualización de solicitudes de contratación.
    public function test_visualizacion_de_contrataciones()
    {
        $user = User::where('id', 35)->first();
        $test_request = $this->actingAs($user)->get('/api/v1/hiring/show');
        $test_request->assertStatus(200);
    }

    // - [ ]  Visualizar contratación seleccionada.
    // - [ ]  Actualizar solicitud de contratación.
    // - [ ]  Cancelar una solicitud de contratación.

    // 🟣 Comentar, sugerir y calificar servicios.
    // - [ ]  Comentar, sugerir y calificar servicio.
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
