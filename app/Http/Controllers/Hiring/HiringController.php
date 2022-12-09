<?php

namespace App\Http\Controllers\Hiring;

use App\Http\Controllers\Controller;
use App\Http\Resources\HiringCliResource;
use App\Http\Resources\HiringTecResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Models\ServiceRequestCli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HiringController extends Controller
{
    // Creacion del consturctor
    public function __construct()
    {
        // Uso del gate para que pueda Solicitar servicios el cliente
        $this->middleware('can:manage-hiring');

        // Implementacion de Politica
        $this->authorizeResource(ServiceRequestCli::class, 'hiring');
    }


    // Se crea una contratacion a un servicio
    public function create(Request $request, Service $service)
    {
        // Validación de los datos de entrada
        $request->validate([
            'device' => ['required', 'string', 'min:1', 'max:50'],
            'model' => ['required', 'string', 'min:1', 'max:50'],
            'brand' => ['required', 'string', 'min:1', 'max:50'],
            'serie' => ['nullable', 'string', 'min:1', 'max:100'],
            'description_problem' => ['required', 'string', 'min:5', 'max:300'],
        ]);

        // Se crea instancia del la solicitud de afiliacion
        $hiring = new ServiceRequestCli($request->all());

        // Se agrega el estado pendiente a la solicitud 0=pendiente
        $hiring->state = 0;

        // Se agrega la fecha de creacion de la solicitud
        $hiring->date_issue = date('Y-m-d');

        // Agregamos el id del servicio al que pertenece
        $hiring->service_id = $service->id;

        // Se obtiene el usuario cliente autenticado
        $user = Auth::user();

        // Se almacena la solicitud de contratacion para este usuario
        $user->service_request_cli()->save($hiring);

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Service request created successfully');
    }


    // Mostrar las solicitudes de servicio hechas por el cliente autenticado
    public function index()
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Se obtine las solitiud de servicio hecha por el cliente
        $hirings = $user->service_request_cli;

        // Validamos si existen solicitudes de afiliaciones
        if (!$hirings->first()) {
            return $this->sendResponse(message: 'There are no service requests for this customer');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Service request list has been generated successfully', result: [
            'service_requests' => HiringCliResource::collection($hirings)
        ]);
    }

    // Mostrar una solcitud de servicio hecha por el usaurio cliente
    public function show(ServiceRequestCli $hiring)
    {
        // Validamos si existen solicitudes de servicio
        if (!$hiring) {
            return $this->sendResponse(message: 'Service request does not exist');
        }

        // valida si la solicitud tiene
        // * en proceso, finalizado o comentado por el tecnico
        if (($hiring->state == 3 || $hiring->state == 4 || $hiring->state == 5)) {
            // Invoca el controlador padre para la respuesta json
            return $this->sendResponse(message: 'The customer service request was returned successfully', result: [
                'service_request' => new HiringCliResource($hiring),
                'attention' => new HiringTecResource($hiring->service_request_tec),
                'of_service' => new ServiceResource($hiring->service),
                'created_by' => new ProfileResource($hiring->service->user)
            ]);
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The customer service request was returned successfully', result: [
            'service_request' => new HiringCliResource($hiring),
            'important' => 'the service request has not yet been attended',
            'of_service' => new ServiceResource($hiring->service),
            'created_by' => new ProfileResource($hiring->service->user)
        ]);
    }

    // Se crea una contratacion a un servicio
    public function update(Request $request, ServiceRequestCli $hiring)
    {
        // Validamos
        // * Si la solicitud no esta pendiente o cancelado
        if ($hiring->state == 1 || $hiring->state == 3 || $hiring->state == 4 || $hiring->state == 5) {
            return $this->sendResponse(message: 'This action is unauthorized.');
        }

        // Validación de los datos de entrada
        $request->validate([
            'device' => ['required', 'string', 'min:1', 'max:50'],
            'model' => ['required', 'string', 'min:1', 'max:50'],
            'brand' => ['required', 'string', 'min:1', 'max:50'],
            'serie' => ['nullable', 'string', 'min:1', 'max:100'],
            'description_problem' => ['required', 'string', 'min:5', 'max:300'],
        ]);

        // Se agrega la fecha de creacion de la solicitud
        $request->date_issue = date('Y-m-d');

        // Se actualiza la informacion del servicio
        $hiring->update($request->all());

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Service request updated successfully');
    }

    // Cancelar una solicitud de servicio
    public function destroy(ServiceRequestCli $hiring)
    {
        // Validamos
        // * Si la solicitud no esta pendiente o cancelado
        if ($hiring->state == 1 || $hiring->state == 3 || $hiring->state == 4 || $hiring->state == 5) {
            return $this->sendResponse(message: 'This action is unauthorized.');
        }

        // Obtener el estado de la solcitud de servicio
        $hiring_state = $hiring->state;

        // Cambiamos de pendiente a cancelado
        if ($hiring_state == 0) {
            // Cambia el estado a cancela
            $hiring->state = 2;
            $message = 'cancel';
        }

        // Cambiamos de cancelado a pendiente
        if ($hiring_state == 2) {
            // Cambia el estado a pendiente
            $hiring->state = 0;
            $message = 'pending';
        }

        // Guardar en la BDD
        $hiring->save();

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: "Service request $message successfully");
    }
}
