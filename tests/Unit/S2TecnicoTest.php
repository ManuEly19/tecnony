<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Models\User;
use Tests\TestCase;

class S2TecnicoTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    // CREACION DE PRUEBAS UNITARIAS POR ENDPOINT
    // Las APIs probadas se dividen para:
    // ⚪ General | 🟢 Admin | 🔵 Técnico | 🟣 Cliente

    // 🔵 Registro de usuario para el perfil técnico.
    // - [ ]  Registro de técnico
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

    // 🔵 6. Solicitación de afiliación.
    // - [ ]  Solicitación de afiliación
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

    // 🔵 Gestión de servicios.
    // - [ ]  Visualización de servicios generados.
    public function test_visualizacion_de_servicios_generados()
    {
        $user = User::where('id', 7)->first();
        $test_request = $this->actingAs($user)->get('/api/v1/service');
        $test_request->assertStatus(200);
    }
    // - [ ]  Creación de un servicio
    // - [ ]  Activación o desactivación de un servicios

    // 🔵 8. Aprobación de contratos.
    // - [ ]  Visualización de solicitudes de contratación.
    // - [ ]  Visualización de solicitud de contratación seleccionado.
    // - [ ]  Aprobación de solicitud de contratación.
    public function test_aprobacion_de_solicitud_de_contratacion()
    {
        $user = User::where('id', 9)->first();
        $test_request = $this->actingAs($user)->get(sprintf('/api/v1/manage-hiring/approve/%u', 36));
        $test_request->assertStatus(200);
    }

    // 🔵 9. Visualización de comentarios, sugerencias y calificación de servicios.
    // - [ ]  Visualización de comentarios.
    public function test_visualizacion_de_comentarios()
    {
        $user = User::where('id', 7)->first();
        $test_request = $this->actingAs($user)->get('/api/v1/view-satisfaction-form');
        $test_request->assertStatus(200);
    }
}
