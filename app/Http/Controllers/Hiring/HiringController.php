<?php

namespace App\Http\Controllers\Hiring;

use App\Http\Controllers\Controller;
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
}
