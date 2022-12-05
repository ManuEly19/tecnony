<?php

namespace App\Http\Controllers\Affiliation;

use App\Http\Controllers\Controller;
use App\Http\Resources\AffiliationAdResource;
use App\Http\Resources\AffiliationTecResource;
use App\Http\Resources\ProfileResource;
use App\Models\AffiliationAd;
use App\Models\AffiliationTec;
use App\Models\User;
use App\Notifications\AffiliationDeclinedNotifi;
use App\Notifications\AffiliationNotifi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliationAdController extends Controller
{
    // Creación del constructor
    public function __construct()
    {
        // Uso del gate para que pueda Solicitar afiliacion
        $this->middleware('can:manage-affiliations');

        // https://laravel.com/docs/9.x/authorization#authorizing-resource-controllers
        $this->authorizeResource(AffiliationAd::class, 'affiliation');
    }


    // Mostrar todas las afiliaciones sin atener
    public function index()
    {
        // Se obtiene las afiliaciones sin atender
        $affiliations = AffiliationTec::where('state', 1)->get();

        // Validamos si existen solicitudes de afiliaciones
        if (!$affiliations->first()) {
            return $this->sendResponse(message: 'There are no pending affiliations');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The affiliation list has been generated successfully', result: [
            'affiliations' => AffiliationTecResource::collection($affiliations)
        ]);
    }


    // Mostra las afiliaciones que atendio el usuario tecnico
    public function showAll()
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Se obtine las solitiud de afiliacion atendidas por el usuario admin
        $affiliations = $user->affiliation_ad;

        // Validamos si existen solicitudes de afiliaciones
        if (!$affiliations->first()) {
            return $this->sendResponse(message: 'No affiliations answered by this admin');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The affiliation answered list has been generated successfully', result: [
            'affiliations' => AffiliationAdResource::collection($affiliations)
        ]);
    }


    // Se necesita POLITICA de propiedad
    // Mostra una afiliacion atendida por el usuario tecnico
    public function show(AffiliationAd $affiliation)
    {
        // Validamos si existen solicitudes de afiliaciones
        if (!$affiliation) {
            return $this->sendResponse(message: 'the affiliation request does not exist');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The affiliation answered list has been generated successfully', result: [
            'tecnico' => new ProfileResource($affiliation->affiliation_tec->user),
            'affiliation' => new AffiliationTecResource($affiliation->affiliation_tec),
            'answered' => new AffiliationAdResource($affiliation)
        ]);
    }


    // Se crea una respuesta de solicitud
    public function create(Request $request, AffiliationTec $affiliationtec)
    {
        // Validación de los datos de entrada
        $request->validate([
            'state' => ['required', 'numeric', 'digits:1'],

            'observation' => ['nullable', 'string', 'max:500'],
        ]);

        // Se crea instancia del la solicitud de afiliacion
        $affiliation = new AffiliationAd($request->all());

        // Se agrega la fecha de creacion del estado
        $affiliation->date_acceptance = date('Y-m-d');

        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Se obtiene el usuario tecnico
        $userTec = $affiliationtec->user;

        // Asignamos la relacion de las afiliaciones
        $affiliation->affiliation_tec_id = $affiliationtec->id;

        // Se verifica si la afiliacion ha sido aceptada
        if ($affiliation->state == 2) {
            // Actualizamos el estado de la afiliacion del lado del tecnico
            $affiliationtec->state = 2;
            // actualizamos en la base de datos
            $affiliationtec->update();

            // Se procede a invocar la función para en envío de una notificación de registro
            $this->sendNotification1($userTec);
        }

        // Se verifica si la afiliacion ha sido rechazada
        if ($affiliation->state == 3) {
            // Actualizamos el estado de la afiliacion del lado del tecnico
            $affiliationtec->state = 3;
            // actualizamos en la base de datos
            $affiliationtec->update();

             // Se procede a invocar la función para en envío de una notificación de registro
            $this->sendNotification2($userTec, $affiliation);
        }

        // Se almacena la solicitud de afiliacion para este usuario
        $user->affiliation_ad()->save($affiliation);

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The response to the affiliation has been created.');
    }


    // Se aptualiza la respuesta de solicitud
    public function update(Request $request, AffiliationAd $affiliation)
    {
        // Validación de los datos de entrada
        $request->validate([
            'state' => ['required', 'numeric', 'digits:1'],

            'observation' => ['nullable', 'string', 'max:500'],
        ]);

        // Se agrega la fecha de creacion del estado
        $request->date_acceptance = date('Y-m-d');

        // Se obtiene el usuario tecnico
        $userTec = $affiliation->affiliation_tec->user;

        // Se verifica si la afiliacion ha sido aceptada
        if ($request->state == 2) {
            // Actualizamos el estado de la afiliacion del lado del tecnico
            $affiliation->affiliation_tec->state = 2;
            // actualizamos en la base de datos
            $affiliation->affiliation_tec->update();
            // Se procede a invocar la función para en envío de una notificación de registro
            $this->sendNotification1($userTec);
        }

        // Se verifica si la afiliacion ha sido rechazada
        if ($request->state == 3) {
            // Actualizamos el estado de la afiliacion del lado del tecnico
            $affiliation->affiliation_tec->state = 3;
            // actualizamos en la base de datos
            $affiliation->affiliation_tec->update();
            // Se procede a invocar la función para en envío de una notificación de registro
            $this->sendNotification2($userTec, $affiliation);
        }

        $affiliation->update($request->all());

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The response to the affiliation has been updated.');
    }


    // Función para enviar notificacion para la solicitud aceptada
    private function sendNotification1(User $user)
    {
        // https://laravel.com/docs/9.x/notifications#sending-notifications
        $user->notify(
            new AffiliationNotifi(
                user_name: $user->getFullName()
            )
        );
    }

    // Función para enviar notificacion para la solicitud aceptada
    private function sendNotification2(User $user, AffiliationAd $affiliationAd)
    {
        // https://laravel.com/docs/9.x/notifications#sending-notifications
        $user->notify(
            new AffiliationDeclinedNotifi(
                user_name: $user->getFullName(),
                observation: $affiliationAd->observation
            )
        );
    }
}
