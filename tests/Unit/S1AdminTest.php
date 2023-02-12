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

    // - Recuperación de contraseña
    public function test_recuperar_contraseña()
    {
        $test_request = $this->post('/api/v1/forgot-password', [
            "email" => "chance12@example.org",
        ]);
        $test_request->assertStatus(200);
    }

    // - Cerrar sesion
    public function test_cerrar_sesion()
    {
        $user = User::factory()->make(['role_id' => 1]);
        $test_request = $this->actingAs($user)->post('/api/v1/logout');
        $test_request->assertStatus(200);
    }

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

    // - Modificar avatar
    public function test_modificacion_de_avatar()
    {
        $user = User::factory()->make(['role_id' => 1]);
        $avatar = [
        ];
        $test_request = $this->actingAs($user)->post('/api/v1/profile/avatar', $avatar);
        $test_request->assertStatus(200);
    }

    // 🟢 Gestión de solicitudes de afiliación.
    // - [*] Visualizar solicitud de afiliación
/*     public function test_visualizacion_de_solicitudes_de_afiliacion()
    {
        $user = User::where('id', 1)->first();
        $test_request = $this->actingAs($user)->get('/api/v1/manage/affiliations');
        $test_request->assertStatus(200);
    } */

    // - Visualizar solicitud de afiliación seleccionada
    public function test_visualizacion_de_solicitudes_de_afiliacion_seleccionada()
    {
        $user = User::where('id', 1)->first();
        $test_request = $this->actingAs($user)->get(sprintf('/api/v1/manage/affiliation/%u', 14));
        $test_request->assertStatus(200);
    }

    // - Aceptación de solicitud de afiliación
    public function test_aceptacion_de_solicitud_de_afiliacion()
    {
        $user = User::where('id', 1)->first();
        $answer = [
            "state" => 2,
            "observation" => "La información esta en orden",
        ];
        $test_request = $this->actingAs($user)->post(sprintf('/api/v1/manage/affiliation/create/%u', 14), $answer);
        $test_request->assertStatus(200);
    }

    // 🟢 Visualización de comentarios, sugerencias y calificación de los técnicos.
    // - Visualizar técnicos
    public function test_visualizar_tecnicos()
    {
        $user = User::where('id', 1)->first();
        $test_request = $this->actingAs($user)->get('/api/v1/manage-tec');
        $test_request->assertStatus(200);
    }
    // - [ ]  visualizar los comentarios de un técnico seleccionado
/*     public function test_visualizacion_de_comentarios_de_un_tecnico()
    {
        $user = User::where('id', 1)->first();
        $test_request = $this->actingAs($user)->get(sprintf('/api/v1/manage-tec/show/%u', 6));
        $test_request->assertStatus(200);
    } */

    // - Activación o desactivación de técnico
    public function test_activacion_o_desactivacion_de_tecnicos()
    {
        $user = User::where('id', 1)->first();
        $change = [
            "observation" => "Tecnico desactivado",
        ];
        $test_request = $this->actingAs($user)->post(sprintf('/api/v1/manage-tec/change-state/%u', 24), $change);
        $test_request->assertStatus(200);
    }
}

