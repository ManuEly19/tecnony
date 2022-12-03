<?php

namespace App\Http\Controllers\Affiliation;

use App\Http\Controllers\Controller;
use App\Http\Resources\AffiliationAdResource;
use App\Http\Resources\AffiliationTecResource;
use App\Http\Resources\ProfileResource;
use App\Models\AffiliationAd;
use App\Models\AffiliationTec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliationAdController extends Controller
{
    // Creación del constructor
    public function __construct()
    {
        // Uso del gate para que pueda Solicitar afiliacion
        $this->middleware('can:manage-affiliations');

        // https://laravel.com/docs/9.x/controllers#controller-middleware
        // Verifica si el usuario esta activo para hacer el update
        $this->middleware('is.user.active')->only('index', 'show', 'showOne');
    }

    // Mostrar todas las afiliaciones sin atener
    public function index()
    {
        // Se obtiene las afiliaciones sin atender
        $affiliations = AffiliationTec::where('state', 1)->get();

        // Validamos si existen solicitudes de afiliaciones
        if (!$affiliations->first()){
            return $this->sendResponse(message: 'There are no pending affiliations');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The affiliation list has been generated successfully', result: [
            'affiliations' => AffiliationTecResource::collection($affiliations)
        ]);
    }

    // Mostra las afiliaciones que atendio el usuario tecnico
    public function show()
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Se obtine las solitiud de afiliacion atendidas por el usuario admin
        $affiliations = $user->affiliation_ad;

        // Validamos si existen solicitudes de afiliaciones
        if (!$affiliations->first()){
            return $this->sendResponse(message: 'No affiliations answered by this admin');
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The affiliation answered list has been generated successfully', result: [
            'affiliations' => AffiliationAdResource::collection($affiliations)
        ]);
    }

        // Se necesita POLITICA de propiedad
        // Mostra las afiliaciones que atendio el usuario tecnico
        public function showOne(AffiliationAd $affiliation)
        {
            // Validamos si existen solicitudes de afiliaciones
            if (!$affiliation){
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
}
