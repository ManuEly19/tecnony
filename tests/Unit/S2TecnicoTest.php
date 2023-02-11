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
    // - [*]  Registro de técnico
/*     public function test_resgistro_de_tecnico()
    {
        $test_request = $this->post('/api/v1/register-tecnico', [
            "username" => "Hikari11+",
            "first_name" => "luz",
            "last_name" => "andrade",
            "personal_phone" => "0952306598",
            "address" => "342 Guamani Oval Apt. 7",

            "cedula" => "1722838942",
            "email" => "hikari11@gmail.com",

            "birthdate" => "1999-08-15",
            "home_phone" => "0258379",

            "password" => "happySad1*",
            "password_confirmation" => "happySad1*"
        ]);
        $test_request->assertStatus(200);
    } */

    // 🔵 6. Solicitación de afiliación.
    // - Solicitación de afiliación
/*     public function test_crear_solicitacion_de_afiliacion()
    {
        $user = User::where('id', 59)->first();
        $affiliation = [
            "profession" => "Ingeniero en sistemas",
            "specialization" => "Informatica computacional",
            "work_phone" => "0983657935",
            "attention_schedule" => "De 8 de la mañana hasta las 3 de la noche",
            "local_name" => "CompuLed",
            "local_address" => "67 Guamani Oval Apt. 7",
            "confirmation" => true,

            'account_number' => 2238491478,
            'account_type' => "Ahorros",
            'banking_entity' => "BANCO DE PICHINCHA"
        ];
        $test_request = $this->actingAs($user)->post('/api/v1/affiliation/create', $affiliation);
        $test_request->assertStatus(200);
    } */

    // 🔵 Gestión de servicios.
    // - [ ]  Visualización de servicios generados.
/*     public function test_visualizacion_de_servicios_generados()
    {
        $user = User::where('id', 7)->first();
        $test_request = $this->actingAs($user)->get('/api/v1/service');
        $test_request->assertStatus(200);
    } */
    // - Creación de un servicio
    public function test_creacion_de_un_servicio()
    {
        $user = User::factory()->make(['role_id' => 2]);
        $service = [
            "name" => "Mantenimientos de computadoras",
            "categories" => "mantenimiento",
            "description" => "Se realiza mantemiento a computadora de todo tipo",
            "price" => 7.00,
            "attention_mode" => 1,
            "attention_description" => "Solo poseemos atencion en el mismo local, necesita",
            "payment_method" => 2,
            "payment_description" => "Poseemos pagos en efectivo y desposito o trasferencia"
        ];
        $test_request = $this->actingAs($user)->post('/api/v1/service/create', $service);
        $test_request->assertStatus(200);
    }

    // - Activación o desactivación de un servicio
    public function test_activacion_o_desactivacion_de_un_servicio()
    {
        $user = User::where('id', 8)->first();
        $test_request = $this->actingAs($user)->get(sprintf('/api/v1/service/destroy/%u', 5));
        $test_request->assertStatus(200);
    }

    // 🔵 8. Aprobación de contratos.
    // - Visualización de solicitudes de contratación.
    public function test_visualizacion_de_solicitudes_de_contratacion()
    {
        $user = User::where('id', 21)->first();
        $test_request = $this->actingAs($user)->get('/api/v1/manage-hiring/shownew');
        $test_request->assertStatus(200);
    }

    // - Visualización de solicitud de contratación seleccionado.
    public function test_visualizacion_de_solicitudes_de_contratacion_seleccionado()
    {
        $user = User::where('id', 8)->first();
        $test_request = $this->actingAs($user)->get(sprintf('/api/v1/manage-hiring/showone/%u', 5));
        $test_request->assertStatus(200);
    }

    // - [*]  Aprobación de solicitud de contratación.
/*     public function test_aprobacion_de_solicitud_de_contratacion()
    {
        $user = User::where('id', 21)->first();
        $test_request = $this->actingAs($user)->get(sprintf('/api/v1/manage-hiring/approve/%u', 87));
        $test_request->assertStatus(200);
    } */

    // 🔵 9. Visualización de comentarios, sugerencias y calificación de servicios.
    // - [*] Visualización de comentarios.
/*     public function test_visualizacion_de_comentarios()
    {
        $user = User::where('id', 7)->first();
        $test_request = $this->actingAs($user)->get('/api/v1/view-satisfaction-form');
        $test_request->assertStatus(200);
    } */
}
