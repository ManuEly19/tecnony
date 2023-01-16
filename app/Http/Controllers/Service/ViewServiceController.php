<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Resources\AffiliationTecResource;
use App\Http\Resources\ServiceResource;
use App\Models\AffiliationTec;
use App\Models\Service;
use App\Models\User;

class ViewServiceController extends Controller
{
    // Listar los servicios de los tecnicos
    public function index()
    {
        // Obtener el id de los tecnicos activos
        $tec = User::select('id')
            ->where('state', 1)
            ->where('role_id', 2)->get();

        // Obtener el id de los tecnicos que no estan afiliados
        $tecaffiliation = AffiliationTec::select('user_id as id')
            ->where('state', '<>', 2)->get();

        // Obtenemos todos los servicios de tecnicos que
        // * Si esta activos y afiliados
        // * Si el servicio esta activo
        $services = Service::where('state', 1)
            ->whereIn('user_id', $tec)
            ->whereNotIn('user_id', $tecaffiliation)->get();

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Lista de servicios generada con éxito', result: [
            'services' => ServiceResource::collection($services)
        ]);
    }

    // Muestra un servicio selecionado
    public function show(Service $service)
    {
        // Obtener los datos de la afiliacion del tecnico
        $tecaffiliation = AffiliationTec::where('user_id', $service->user->id)->first();

        // Obtener los datos de la afiliacion del tecnico
        $tec = User::where('id', $service->user->id)->first();

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Detalles del servicio', result: [
            'service' => new ServiceResource($service),
            'tec_avatar' => $tec->getAvatarPath(),
            'created_by' => new AffiliationTecResource($tecaffiliation)
        ]);
    }
}
