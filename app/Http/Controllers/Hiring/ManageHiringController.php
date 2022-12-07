<?php

namespace App\Http\Controllers\Hiring;

use App\Http\Controllers\Controller;
use App\Http\Resources\HiringCliResource;
use App\Models\Service;
use App\Models\ServiceRequestCli;
use App\Models\ServiceRequestTec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageHiringController extends Controller
{
    // Creacion del consturctor
    public function __construct()
    {
        // Uso del gate para que pueda Solicitar servicios el cliente
        $this->middleware('can:approve-hiring');

        // Implementacion de Politica
        $this->authorizeResource(ServiceRequestTec::class, 'hiring');
    }

    // Mostrar las solicitudes de servicio hechas por el cliente al tecnico
    public function shownew()
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Se obtiene el id del usario autenticado
        $userid = $user->id;

        // Obtenemos los id de los servicios del del usuario
        $serviceid = Service::select('id')
            ->where('user_id', $userid)->get();

        // Obtenemos las solcitudes de servicio hechas por cliente al tecnico
        // * Si estan pendiente o en proceso
        // * Si pertenece a uno de los servicios del tecnico autenticado
        $hirings = ServiceRequestCli::whereIn('state', [0, 3])
            ->whereIn('service_id', $serviceid)->get();

        // Validamos si existen solicitudes de afiliaciones para el tecnico
        if (!$hirings->first()) {
            return $this->sendResponse(message: 'There are no service requests for this technical');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The service request for the technician was returned successfully', result: [
            'service-requests' => HiringCliResource::collection($hirings)
        ]);
    }

    // Mostrar las solicitudes de servicio hechas por el cliente a detalle
    // Aprobar o rechazar una solicitud de servicio
    // Finalizar una solicitud de servicio aprovada por el tecnico


    // Mostrar las solicitudes de servicios finalizada
    // Mostrar las solicitudes de servicios finalizada a detalle
    // Actualizar una solicitud de servicio aprovada por el tecnico

}
