<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceRequestCli;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceRequestCliSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Creamos 20 solicitudes de servicio del lado del cliente
        ServiceRequestCli::factory()->count(30)->create();
        // Obtenemos todas las solicitudes de servicio
        $service_request_clis = ServiceRequestCli::all();

        //Obtenemos los usuarios clientes
        $users_clientes = User::where('role_id', 3)->get();

/*         // un cliente puede realizar muchas solicitud de servicio
        $serviceRequestClis->each(function($serviceRequestCli) use ($users_clientes)
        {
            $serviceRequestCli->user_cli()->attach($users_clientes->shift(2));
        });

        // Obtener todos los servicios
        $services = Service::all();

        // Un servicio tiene muchas solitudes de servicio
        $serviceRequestClis->each(function($serviceRequestCli) use ($services)
        {
            $serviceRequestCli->user_cli()->attach($services->shift(2));
        }); */
    }
}
