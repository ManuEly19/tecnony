<?php

namespace App\Http\Controllers\Hiring;

use App\Http\Controllers\Controller;
use App\Http\Resources\HiringCliResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ServiceResource;
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
            'service_requests' => HiringCliResource::collection($hirings)
        ]);
    }

    // Mostrar las solicitudes de servicio hechas por el cliente a detalle
    public function showone(ServiceRequestCli $hiring)
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Obtenemos los id de los servicios del del usuario
        $service = Service::where('id', $hiring->service_id)->first();

        // Enviamos mensaje de no pertenecia
        if ($service->user_id != $user->id) {
            return $this->sendResponse(message: 'This service request is not for this technical');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The service request for the technician was returned successfully', result: [
            'service_requests' => new HiringCliResource($hiring),
            'created_by' => new ProfileResource($hiring->user),
            'of_service' => new ServiceResource($hiring->service)
        ]);
    }

    // Aprobar una solicitud de servicio
    public function approve(ServiceRequestCli $hiring)
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Obtenemos los id de los servicios del del usuario
        $service = Service::where('id', $hiring->service_id)->first();

        // Enviamos mensaje de no pertenecia
        if ($service->user_id != $user->id) {
            return $this->sendResponse(message: 'This service request is not for this technical');
        }

        // Validamos solo por si acaso
        // * Si la solicitud no esta en proceso
        if ($hiring->state == 3) {
            return $this->sendResponse(message: 'This service request is already being attended');
        }

        // Cambiar de estado a la solicitud a en proceso
        $hiring->state = 3;

        // Guardar cambios
        $hiring->save();

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The service request has been approved');
    }

    // Rechazar una solicitud se serivicio
    public function decline(ServiceRequestCli $hiring)
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Obtenemos los id de los servicios del del usuario
        $service = Service::where('id', $hiring->service_id)->first();

        // Enviamos mensaje de no pertenecia
        if ($service->user_id != $user->id) {
            return $this->sendResponse(message: 'This service request is not for this technical');
        }

        // Validamos solo por si acaso
        // * Si la solicitud no esta en proceso
        if ($hiring->state == 3) {
            return $this->sendResponse(message: 'This service request is already being attended');
        }

        // Cambiar de estado a la solicitud a en proceso
        $hiring->state = 1;

        // Guardar cambios
        $hiring->save();

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The service request has been denied');
    }

    // Finalizar una solicitud de servicio aprovada por el tecnico
    public function create(Request $request, ServiceRequestCli $hiring)
    {
        // Validación de los datos de entrada
        $request->validate([
            'diagnosis' => ['required', 'string', 'min:5', 'max:500'],

            'incident_resolution' => ['required', 'string', 'min:5', 'max:500'],
            'warranty' => ['nullable', 'string', 'min:5', 'max:300'],
            'spare_parts' => ['nullable', 'string', 'min:5', 'max:500'],
            'price_spare_parts' => ['nullable', 'numeric'],
            'final_price' => ['required', 'numeric'],
        ]);

        // Se crea instancia del la solicitud de servicio del lado del tecnico
        $hiringtec = new ServiceRequestTec($request->all());

        // Se agrega la fecha de creacion del estado
        $hiringtec->end_date = date('Y-m-d');

        // Agregamos la relacion entre las solicitudes del lado del tecnio y cliente
        $hiringtec->service_request_cli_id = $hiring->id;

        // Se agrega el estado finalizado a la solicitud del lado del tecnico
        $hiringtec->state = 4;

        // Se agrega el estado finalizado a la solicitud del lado del cliente
        $hiring->state = 4;
        // Actualizamos en la base de datos
        $hiring->update();

        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Se almacena la solicitud de afiliacion para este usuario
        $user->service_request_tec()->save($hiringtec);

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Service request completed successfully');
    }

    // Mostrar las solicitudes de servicios finalizada
    public function index()
    {
    }

    // Mostrar las solicitudes de servicios finalizada a
    public function show()
    {
    }

    // Actualizar una solicitud de servicio aprovada por el tecnico
    public function update()
    {
    }
}
