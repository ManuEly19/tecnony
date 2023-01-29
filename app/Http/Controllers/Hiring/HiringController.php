<?php

namespace App\Http\Controllers\Hiring;

use App\Http\Controllers\Controller;
use App\Http\Resources\AffiliationTecResource;
use App\Http\Resources\HiringCliResource;
use App\Http\Resources\HiringTecResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Models\ServiceRequestCli;
use App\Models\User;
use App\Notifications\NuevoComprobanteDePagoNotifi;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
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

            'payment_method' => ['required', 'numeric', 'digits:1'],
        ]);

        // Validamos si el metodo de pago sea 1. Efectivo o 2.Deposito
        if (($request->payment_method < 1) or ($request->payment_method > 2)) {
            return $this->sendResponse(message: 'Este método de pago no existe, ingrese uno valido');
        }

        // Validamos si el metodo de pago sea 1. Efectivo o 2.Deposito
        if (($service->payment_method == 1) and ($request->payment_method != 1)) {
            return $this->sendResponse(message: 'Este método de pago no está disponible para este servicio, ingrese uno valido');
        }

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
        return $this->sendResponse(message: 'Solicitud de servicio creada con éxito');
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
            return $this->sendResponse(message: 'Usted no tiene solicitudes de servicio');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La lista de solicitudes de servicio se ha generado correctamente', result: [
            'service_requests' => HiringCliResource::collection($hirings)
        ]);
    }

    // Mostrar una solcitud de servicio hecha por el usaurio cliente
    public function show(ServiceRequestCli $hiring)
    {
        // Validamos si existen solicitudes de servicio
        if (!$hiring) {
            return $this->sendResponse(message: 'La solicitud de servicio no existe');
        }

        // valida si la solicitud tiene estado en:
        // * en proceso, finalizado, pagado o comentado
        if (($hiring->state == 3 || $hiring->state == 4 || $hiring->state == 5 || $hiring->state == 6)) {
            // Invoca el controlador padre para la respuesta json
            return $this->sendResponse(message: 'La solicitud de servicio fue devuelta con éxito', result: [
                'comprobante' => $hiring->getImagePath(),
                'service_request' => new HiringCliResource($hiring),
                'attention' => new HiringTecResource($hiring->service_request_tec),
                'of_service' => new ServiceResource($hiring->service),
                'datos_tecnico' => [
                    'avatar' => $hiring->service->user->getAvatarPath(),
                    'cedula' => $hiring->service->user->cedula,
                    'correo' => $hiring->service->user->email,
                ],
                'created_by' => new AffiliationTecResource($hiring->service->user->affiliation_tec)
            ]);
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitud de servicio aún no ha sido atendida', result: [
            'service_request' => new HiringCliResource($hiring),
            'of_service' => new ServiceResource($hiring->service),
            'datos_tecnico' => [
                'avatar' => $hiring->service->user->getAvatarPath(),
                'cedula' => $hiring->service->user->cedula,
                'correo' => $hiring->service->user->email,
            ],
            'created_by' => new AffiliationTecResource($hiring->service->user->affiliation_tec)
        ]);
    }

    // Se crea una contratacion a un servicio
    public function update(Request $request, ServiceRequestCli $hiring)
    {
        // Validamos
        // * Si la solicitud no esta pendiente o cancelado
        if ($hiring->state == 1 || $hiring->state == 3 || $hiring->state == 4 || $hiring->state == 5 || $hiring->state == 6) {
            return $this->sendResponse(message: 'Esta acción no está autorizada');
        }

        // Validación de los datos de entrada
        $request->validate([
            'device' => ['nullable', 'string', 'min:1', 'max:50'],
            'model' => ['nullable', 'string', 'min:1', 'max:50'],
            'brand' => ['nullable', 'string', 'min:1', 'max:50'],
            'serie' => ['nullable', 'string', 'min:1', 'max:100'],
            'description_problem' => ['nullable', 'string', 'min:5', 'max:300'],

            'payment_method' => ['nullable', 'numeric', 'digits:1'],
        ]);

        // Validamos si el metodo de pago sea 1. Efectivo o 2.Deposito
        if (($request->payment_method < 1) or ($request->payment_method > 2)) {
            return $this->sendResponse(message: 'Este método de pago no existe, ingrese uno valido');
        }

        // Obtenemos el servicio donde proviene la contratacion
        $service = Service::where('id', $hiring->service_id)->first();

        // Validamos si el metodo de pago sea 1. Efectivo o 2.Deposito
        if (($service->payment_method == 1) and ($request->payment_method != 1)) {
            return $this->sendResponse(message: 'Este método de pago no está disponible para este servicio, ingrese uno valido');
        }

        // Se agrega la fecha de creacion de la solicitud
        $hiring->date_issue = date('Y-m-d');

        // Se actualiza la informacion del servicio
        $hiring->update($request->all());

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Solicitud de servicio actualizada con éxito');
    }

    // Cancelar una solicitud de servicio
    public function destroy(ServiceRequestCli $hiring)
    {
        // Validamos
        // * Si la solicitud no esta pendiente o cancelado
        if ($hiring->state == 1 || $hiring->state == 3 || $hiring->state == 4 || $hiring->state == 5 || $hiring->state == 6) {
            return $this->sendResponse(message: 'Esta acción no está autorizada');
        }

        // Obtener el estado de la solcitud de servicio
        $hiring_state = $hiring->state;

        // Cambiamos de pendiente a cancelado
        if ($hiring_state == 0) {
            // Cambia el estado a cancela
            $hiring->state = 2;
            $message = 'cancelada';
        }

        // Cambiamos de cancelado a pendiente
        if ($hiring_state == 2) {
            // Cambia el estado a pendiente
            $hiring->state = 0;
            $message = 'rehabilitada';
        }

        // Guardar en la BDD
        $hiring->save();

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: "La solicitud de servicio ha sido $message con éxito");
    }

    // Subir el comprobante de pago de un servicio finalizado
    public function uploadReceipt(Request $request, ServiceRequestCli $hiring)
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // validar propiedad
        if ($hiring->user_id != $user->id) {
            return $this->sendResponse(message: 'Esta contratación de servicio no le pertenece.');
        }

        // Validamos si el contrato no esta finalizado
        if ($hiring->state != 4) {
            return $this->sendResponse(message: 'El servicio contratado aún no está finalizado.');
        }

        // Validación de los datos de entrada
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,png,jpeg', 'max:10000'],
        ]);

        // Pasando a la función la imagen del request
        $image = $request['image'];

        $con = Cloudinary::upload($image->getRealPath(), ["carpeta" => "comprobantePago"]);

        $direciones = $con->getSecurePath();

        // Se hace uso del Trait para asociar esta imagen con el modelo
        $hiring->attachImage($direciones);

        // Se procede a invocar la función para en envío de la notificación
        $this->sendNotificationComprobante($user, $hiring->service_request_tec->user, $hiring);

        return $this->sendResponse('Comprobante de pago subido con éxito');
    }

    // Función para enviar notificacion para la el comprobante de pago
    private function sendNotificationComprobante(User $user, User $tec, ServiceRequestCli $hiring)
    {
        // https://laravel.com/docs/9.x/notifications#sending-notifications
        $user->notify(
            new NuevoComprobanteDePagoNotifi(
                user_cli: $user->getFullName(),
                device: $hiring->device,
                model: $hiring->model,
                user_tec: $tec->getFullName()
            )
        );
    }
}
