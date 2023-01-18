<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Resources\HiringCliResource;
use App\Http\Resources\HiringTecResource;
use App\Http\Resources\ProfileResource;
use App\Models\SatisfactionForm;
use App\Models\ServiceRequestCli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateCommentController extends Controller
{
    // Creacion del consturctor
    public function __construct()
    {
        // Uso del gate para que pueda crear el formulario de satisfacion
        $this->middleware('can:make-satisfaction-form');
    }

    // Mostrar las solcitudes de servicio sin commentar
    public function index()
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // obtenemos las solcicitudes de servicio sin comentar el cliente autenticado
        $hiring = ServiceRequestCli::where('user_id', $user->id)
            ->where('state', 5)->get();

        // Validamos si no existen solicitudes a comentar para este cliente
        if (!$hiring->first()) {
            return $this->sendResponse(message: 'Todas las solicitudes de servicio ya están comentadas');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitudes de servicio a comentar se devolvieron con éxito', result: [
            'service_requests' => HiringCliResource::collection($hiring)
        ]);
    }

    // Mostrar las solicitudes de servicios finalizada
    public function show(ServiceRequestCli $hiring)
    {
        // Validamos si existen solicitudes de servicio
        if (!$hiring) {
            return $this->sendResponse(message: 'Esta solicitud de servicio no existe');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitud de servicio atendida fue devuelta con éxito', result: [
            'service_request' => new HiringCliResource($hiring),
            'attention' => new HiringTecResource($hiring->service_request_tec),
            'attended_by' => new ProfileResource($hiring->service_request_tec->user)
        ]);
    }

    // Crear formulario de satisfacion a una solicitud de servicio atendida
    public function create(Request $request, ServiceRequestCli $hiring)
    {
        // Validamos solo por si acaso
        // * Si la solicitud que ya estan comentada
        if ($hiring->state != 5) {
            return $this->sendResponse(message: 'Esta acción no está autorizada');
        }

        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Validamos si la solcitiud de servicio es del cliente
        if ($user->id != $hiring->user_id) {
            return $this->sendResponse(message: 'No eres el propietario de esta solicitud de servicio');
        }

        // Validación de los datos de entrada
        $request->validate([
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
            'suggestion' => ['nullable', 'string', 'min:5', 'max:1000'],
            'qualification' => ['required', 'numeric'],
        ]);

        // Se crea instancia del la solicitud de servicio del lado del tecnico
        $comment = new SatisfactionForm($request->all());

        // Se redonde de la calificacion del formulario a 2 decimales
        $comment->qualification = round($comment->qualification);

        // Cambiar el estado a comentado
        $hiring->state = 6;

        // Guardamos el estado
        $hiring->save();

        // Se almacena le commentario para esta solicitud de servicio
        $hiring->satisfaction_form()->save($comment);

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'El formulario de satisfacción ha sido creado con éxito.');
    }
}
