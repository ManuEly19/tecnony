<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Str;

class GeneralTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */

    // CREACION DE PRUEBA UNITARIA POR ENDPOINT
    // Las APIs probadas se dividen para:
    // ⚪ General | 🟢 Admin | 🔵 Técnico | 🟣 Cliente

    // ⚪ 1. Iniciar sesión, cerrar sesión y recuperar contraseña
    // - Iniciar sesión
    public function test_inicio_de_sesion()
    {
        
    }

    // ⚪ 2. Modificar perfil de usuario
    // - Modificar perfil
    public function test_modificacion_de_perfil()
    {
        
    }

    // -------------------------------------------------

    // 🟢 3. Gestión de solicitudes de afiliación
    // - Visualizar solicitudes de afiliacion atendidas
    public function test_visualizacion_de_solicitudes_de_afiliacion()
    {

    }

    // 🟢 4. Visualización de comentarios, sugerencias y calificación de los técnicos
    // - Visualizar comentarios de un tecnico
    public function test_visualizacion_de_comentarios_de_un_tecnico()
    {

    }

    // -------------------------------------------------

    // 🔵 5. Registro de usuario para el perfil técnico
    // - Registrar usuario técnico
    public function test_resgistro_de_tecnico()
    {

    }

    // 🔵 6. Solicitación de afiliación
    // - Crear solicitar afiliacion
    public function test_crear_solicitud_de_afiliacion()
    {

    }

    // 🔵 7. Gestión de servicios
    // - Visualizar servicios generados
    public function test_visualizacion_de_servicios_generados()
    {

    }

    // 🔵 8. Aprobación de servicios
    // - Aprobar servicio
    public function test_aprobacion_de_servicio()
    {

    }

    // 🔵 9. Visualización de comentarios, sugerencias y calificación de los servicios
    // - Visualizar comentarios ...
    public function test_visualizacion_de_comentarios()
    {

    }

    // -------------------------------------------------

    // 🟣 10. Iniciar sesión, cerrar sesión y recuperar contraseña para el cliente
    // - Iniciar sesión
    public function test_iniciar_sesion_para_clientes()
    {

    }

    // 🟣 11. Registro de usuario para el perfil cliente
    // - Registrar usuario cliente
    public function test_resgistro_de_cliente()
    {

    }

    // 🟣 12. Visualización de servicios
    // - Visualizar servicios
    public function test_visualizacion_de_servicios()
    {

    }

    // 🟣 13. Contratación de servicios
    // - Contratar servicio
    public function test_contratacion_de_servicio()
    {

    }

    // 🟣 14. Gestión de solicitudes de servicios
    // - Visualizar contrataciones
    public function test_visualizacion_de_contrataciones()
    {

    }

    // 🟣 15. Comentar, sugerir y calificar los servicios
    // - Comentar ... un servicio
    public function test_comentar_un_servicio()
    {

    }
}
