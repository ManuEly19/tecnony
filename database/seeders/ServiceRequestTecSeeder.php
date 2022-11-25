<?php

namespace Database\Seeders;

use App\Models\ServiceRequestCli;
use App\Models\ServiceRequestTec;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceRequestTecSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtenemos todo las solicitudes del lado del cleinte
        $service_request_clis = ServiceRequestCli::all();

       /*  //Por cada solicitud del lado del cliente se tiene una solicitud del lado del tecnico
        $service_request_clis->each(function($service_request_cli)
        {
            $service_request_cli->service_request_tec()->save(ServiceRequestTec::factory()->make());
        });

        //obtenemos todas las solicitudes del lado del tecnico
        $service_request_tecs = ServiceRequestTec::all();

        // Obtenemos los usarios tecnicos
        $users_tecnicos = User::where('role_id', 2)->get();

        // Un tecnico atiende muchas solitudes de servicio
        $service_request_tecs->each(function($service_request_tec) use ($users_tecnicos)
        {
            $service_request_tec->users()->attach($users_tecnicos->shift(3));
        }); */
    }
}
