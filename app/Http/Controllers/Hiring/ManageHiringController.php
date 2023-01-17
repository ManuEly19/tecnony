<?php

namespace App\Http\Controllers\Hiring;

use App\Http\Controllers\Controller;
use App\Http\Resources\HiringCliResource;
use App\Http\Resources\HiringTecResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Models\ServiceRequestCli;
use App\Models\ServiceRequestTec;
use App\Models\User;
use App\Notifications\ContratacionPagadaNotifi;
use App\Notifications\RechazoDeContratacionNotifi;
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
        // * Si estan pendiente o en proceso y si pagar
        // * Si pertenece a uno de los servicios del tecnico autenticado
        $hirings = ServiceRequestCli::whereIn('state', [0, 3])
            ->whereIn('service_id', $serviceid)->get();

        // Validamos si existen solicitudes para este tecnico
        if (!$hirings->first()) {
            return $this->sendResponse(message: 'No tienes solicitudes de servicios pendientes');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitudes de servicio a atender se devolvió con éxito', result: [
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
            return $this->sendResponse(message: 'Usted no es responsable de esta solicitud de servicio');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitud de servicio se devolvió con éxito', result: [
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

        // Obtenemos el servicio donde proviene la contratacion
        $service = Service::where('id', $hiring->service_id)->first();

        // Enviamos mensaje de no pertenecia
        if ($service->user_id != $user->id) {
            return $this->sendResponse(message: 'Usted no es responsable de esta solicitud de servicio');
        }

        // Validamos solo por si acaso
        // * Si la solicitud ya esta en proceso
        if ($hiring->state == 3) {
            return $this->sendResponse(message: 'Esta solicitud de servicio ya está siendo atendida');
        }

        // Cambiar de estado a la solicitud a en proceso
        $hiring->state = 3;

        // Guardar cambios
        $hiring->save();

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitud de servicio ha sido aprobada');
    }

    // Rechazar una solicitud se serivicio
    public function decline(Request $request, ServiceRequestCli $hiring)
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Obtenemos el servicio donde proviene la contratacion
        $service = Service::where('id', $hiring->service_id)->first();

        // Enviamos mensaje de no pertenencia
        if ($service->user_id != $user->id) {
            return $this->sendResponse(message: 'Usted no es responsable de esta solicitud de servicio');
        }

        // Validamos solo por si acaso
        // * Si la solicitud ya esta en proceso
        if ($hiring->state == 3) {
            return $this->sendResponse(message: 'Esta solicitud de servicio ya está siendo atendida');
        }

        // Validación de los datos de entrada
        $request->validate([
            'observation' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        // Guardamos la observacion donde esta el motivo del rechazo
        $hiring->observation = $request->observation;

        // Cambiar de estado a la solicitud a rechazado
        $hiring->state = 1;

        // Guardar cambios
        $hiring->save();

        // Se procede a invocar la función para en envío de una notificación de rechazo
        $this->sendNotification($hiring->user, $hiring, $user);

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitud de servicio ha sido rechazada');
    }

    // Función para enviar notificacion para la solicitud de contrtatacion rechazada
    private function sendNotification(User $user, ServiceRequestCli $hiring, User $tec)
    {
        // https://laravel.com/docs/9.x/notifications#sending-notifications
        $user->notify(
            new RechazoDeContratacionNotifi(
                user_name: $user->getFullName(),
                observation: $hiring->observation,
                user_tec: $tec->getFullName()
            )
        );
    }

    // Finalizar una solicitud de servicio aprovada por el tecnico
    public function create(Request $request, ServiceRequestCli $hiring)
    {
        // Validamos solo por si acaso
        // * Si la solicitud ya ha sido finalizada
        if ($hiring->state == 4 || $hiring->state == 5 || $hiring->state == 6) {
            return $this->sendResponse(message: 'Ya finalizaste la contratación');
        }
        // * Si la solicitud no esta en proceso
        if ($hiring->state != 3) {
            return $this->sendResponse(message: 'Primero tienes que aprobar la solicitud para finalizar la contratación');
        }

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
        return $this->sendResponse(message: 'Solicitud de servicio completada con éxito');
    }

    // Mostrar las solicitudes de servicios finalizada
    public function index()
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Se obtine las solitiud de servicios atendidas por el tecnico
        $hirings = $user->service_request_tec;
        // Validamos si existen solicitudes de afiliaciones
        if (!$hirings->first()) {
            return $this->sendResponse(message: 'No tienes solicitudes de servicio atendidas');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Se ha generado con éxito la lista de solicitudes de servicios atendidos', result: [
            'service_requests' => HiringTecResource::collection($hirings)
        ]);
    }

    // Mostrar las solicitudes de servicios finalizada a
    public function show(ServiceRequestTec $hiring)
    {
        // Validamos si existen solicitudes de servicio
        if (!$hiring) {
            return $this->sendResponse(message: 'Esta solicitud de servicio no existe');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitud de servicio atendida fue devuelta con éxito', result: [
            'service_request' => new HiringCliResource($hiring->service_request_cli),
            'attention' => new HiringTecResource($hiring),
            'of_service' => new ServiceResource($hiring->service_request_cli->service),
            'created_by' => new ProfileResource($hiring->service_request_cli->user)
        ]);
    }

    // NO ES NECESARIO POR AHORA
    // Actualizar una solicitud de servicio aprovada por el tecnico
    public function updateFinalize(Request $request, ServiceRequestTec $hiring)
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Validamos
        // * Si la solicitud le pertenece al tecnico
        if ($hiring->user_id != $user->id) {
            return $this->sendResponse(message: 'Usted no es el propietario de esta solicitud de servicio.');
        }

        // Validamos
        // * Si la solcitud ya tiene comentarios o ya esta pagado en ese caso no se permite actualizar
        if ($hiring->service_request_cli->state == 5 or $hiring->service_request_cli->state == 6) {
            return $this->sendResponse(message: 'Ya no puedes actualizar esta solicitud de servicio.');
        }

        // Validamos
        // * Si la solicitud no esta finalizada
        if ($hiring->state != 4) {
            return $this->sendResponse(message: 'Esta acción no está autorizada');
        }

        // Validación de los datos de entrada
        $request->validate([
            'diagnosis' => ['required', 'string', 'min:5', 'max:500'],

            'incident_resolution' => ['required', 'string', 'min:5', 'max:500'],
            'warranty' => ['nullable', 'string', 'min:5', 'max:300'],
            'spare_parts' => ['nullable', 'string', 'min:5', 'max:500'],
            'price_spare_parts' => ['nullable', 'numeric'],
            'final_price' => ['required', 'numeric'],

        ]);

        // Se agrega la fecha de creacion del estado
        $hiring->end_date = date('Y-m-d');

        // Del request se obtiene unicamente los dos campos
        $request_data = $request->only(['diagnosis', 'incident_resolution', 'warranty', 'spare_parts', 'price_spare_parts', 'final_price']);

        // Se actualiza la informacion del servicio
        $hiring->fill($request_data)->save();

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitud de servicio actualizada con éxito');
    }


    // Marcar como pagado una solicitud se serivicio
    public function paid(ServiceRequestTec $hiring)
    {

        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Enviamos mensaje de no pertenencia
        if ($hiring->user_id != $user->id) {
            return $this->sendResponse(message: 'Usted no es responsable de esta solicitud de servicio');
        }

        // Validamos solo por si acaso
        // * Si la solicitud ya esta en finalizada
        if ($hiring->state != 4) {
            return $this->sendResponse(message: 'Esta solicitud de servicio no esta finalizada');
        }

        // Obtenemos la contratacion del lado del cleinte donde proviene la contratacion
        $hiringCli = ServiceRequestCli::where('id', $hiring->service_request_cli_id)->first();

        //Cambiamos de estado a la contratacion ddel lado del cliente a pagado
        $hiringCli->state = 5;

        // Guardamos los cambios
        $hiringCli->update();

        // Cambiar de estado a la solicitud a pagado
        $hiring->state = 5;

        // Guardar cambios
        $hiring->save();

        // Se procede a invocar la función para en envío de una notificación de rechazo
        $this->sendNotificationPaga($hiringCli->user, $user);

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitud de servicio ha sido pagada');
    }

    // Función para enviar notificacion para la solicitud de contrtatacion pagada
    private function sendNotificationPaga(User $user, User $tec)
    {
        // https://laravel.com/docs/9.x/notifications#sending-notifications
        $user->notify(
            new ContratacionPagadaNotifi(
                user_name: $user->getFullName(),
                user_tec: $tec->getFullName()
            )
        );
    }
}
