<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Models\User;
use Tests\TestCase;

class S1AdminTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    // CREACION DE PRUEBAS UNITARIAS POR ENDPOINT
    // Las APIs probadas se dividen para:
    // ⚪ General | 🟢 Admin | 🔵 Técnico | 🟣 Cliente

    // ⚪ Inicio de sesión, cierre de sesión y recuperación de contraseña.
    // - [*] Inicio de sesión
/*     public function test_inicio_de_sesion()
    {
        $test_request = $this->post('/api/v1/login', [
            "email" => "chance12@example.org",
            "password" => "happySad1*"
        ]);
        $test_request->assertStatus(200);
    } */

    // - [ ]  forgo-passwork

    // - [ ]  Cerrar sesion

    // ⚪ Modificación de perfil de usuario.
    // - [*]  Modificar de perfil
/*     public function test_modificacion_de_perfil()
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
    } */
    // - [ ]  Modificar avatar

    // 🟢 Gestión de solicitudes de afiliación.
    // - [*] Visualizar solicitud de afiliación
/*     public function test_visualizacion_de_solicitudes_de_afiliacion()
    {
        $user = User::where('id', 1)->first();
        $test_request = $this->actingAs($user)->get('/api/v1/manage/affiliations');
        $test_request->assertStatus(200);
    } */
    // - [ ]  Visualizar solicitud de afiliación seleccionada
    // - [ ]  Aceptación de solicitud de afiliación

    // 🟢 Visualización de comentarios, sugerencias y calificación de los técnicos.
    // - [ ]  Visualizar técnicos
    // - [ ]  visualizar los comentarios de un técnico seleccionado
/*     public function test_visualizacion_de_comentarios_de_un_tecnico()
    {
        $user = User::where('id', 1)->first();
        $test_request = $this->actingAs($user)->get(sprintf('/api/v1/manage-tec/show/%u', 6));
        $test_request->assertStatus(200);
    } */
    // - [ ]  Activación o desactivación de técnico
}

