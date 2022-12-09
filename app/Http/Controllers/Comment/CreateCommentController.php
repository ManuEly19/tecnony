<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Resources\HiringCliResource;
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
            ->where('state', 4)->get();

        // Validamos si existen solicitudes a comentar para este cliente
        if (!$hiring->first()) {
            return $this->sendResponse(message: 'All service requests are commented');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The service request to comment was returned successfully', result: [
            'service_requests' => HiringCliResource::collection($hiring)
        ]);
    }

    // Crear formulario de satisfacion a una solicitud de servicio atendida
    public function create(Request $request, ServiceRequestCli $hiring)
    {
        // Validamos solo por si acaso
        // * Si la solicitud no esta finalizada
        if ($hiring->state != 4) {
            return $this->sendResponse(message: 'This action is unauthorized.');
        }

        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Validamos si la solcitiud de servicio es del cliente
        if ($user->id != $hiring->user_id) {
            return $this->sendResponse(message: 'You are not the owner of this service request');
        }

        // Validación de los datos de entrada
        $request->validate([
            'comment' => ['required', 'string', 'min:5', 'max:1000'],
            'suggestion' => ['nullable', 'string', 'min:5', 'max:1000'],
            'qualification' => ['required', 'numeric'],
        ]);

        // Se crea instancia del la solicitud de servicio del lado del tecnico
        $comment = new SatisfactionForm($request->all());

        // Cambiar el estado a comentado
        $hiring->state = 5;
        // Guardamos el estado
        $hiring->save();

        // Se almacena le commentario para esta solicitud de servicio
        $hiring->satisfaction_form()->save($comment);

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The satisfaction form was created successfully');
    }
}
