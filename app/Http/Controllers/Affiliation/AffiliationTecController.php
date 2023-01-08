<?php

namespace App\Http\Controllers\Affiliation;

use App\Http\Controllers\Controller;
use App\Http\Resources\AffiliationAdResource;
use App\Http\Resources\AffiliationTecResource;
use App\Http\Resources\ProfileResource;
use App\Models\AffiliationTec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AffiliationTecController extends Controller
{
    // Creación del constructor
    public function __construct()
    {
        // Uso del gate para que pueda Solicitar afiliacion
        $this->middleware('can:affiliation');
    }

    // Mostrar la afiliacion del tecnico
    public function show()
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Se obtine la solitiud de afiliacion del usuario tecnico
        $affiliation = $user->affiliation_tec;

        // Validamos si el tecnico no tiene afiliacion
        if (!$user->affiliation_tec) {
            return $this->sendResponse(message: 'El técnico no ha realizado la afiliación');
        }

        // valida si la afiliacion tiene
        // * aceptacion o rechazo del admin
        // * Que los estados de la afiliacion es igual del lado del admin y tecnico
        if (($affiliation->state == 2 || $affiliation->state == 3) && $affiliation->state == $affiliation->affiliation_ad->state) {
            // Invoca el controlador padre para la respuesta json
            return $this->sendResponse(message: 'La solicitud de afiliación fue devuelta correctamente', result: [
                'affiliation' => new AffiliationTecResource($affiliation),
                'attention' => new AffiliationAdResource($affiliation->affiliation_ad),
                'attended_by' => new ProfileResource($affiliation->affiliation_ad->user)
            ]);
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitud de afiliación aún no ha sido atendida', result: [
            'affiliation' => new AffiliationTecResource($affiliation)
        ]);
    }

    public function create(Request $request)
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Validamos si el tecnico ya tiene afiliacion
        if ($user->affiliation_tec != null) {
            return $this->sendResponse(message: 'El técnico ya ha realizado la afiliación');
        }

        // Validación de los datos de entrada
        $request->validate([
            'profession' => ['required', 'string', 'min:3', 'max:50'],
            'specialization' => ['required', 'string', 'min:3', 'max:50'],
            'work_phone' => ['required', 'numeric', 'digits:10'],
            'attention_schedule' => ['nullable', 'string', 'max:300'],
            'local_name' => ['nullable', 'string', 'max:50'],
            'local_address' => ['nullable', 'string', 'max:300'],
            'confirmation' => ['nullable', 'boolean'], //Dejamos en nullable TEMPORALMENTE
        ]);

        // Validamos si la confirmacion esta activa
        if ($request->confirmation == false) {
            return $this->sendResponse(message: 'El técnico tiene que aceptar los termino y condiciones');
        }

        // Se crea instancia del la solicitud de afiliacion
        $affiliation = new AffiliationTec($request->all());

        // Se asigana el estado de la solicitud
        $affiliation->state = 1;

        //Seteamos la confirmacion TEMPORALMENTE
        $affiliation->confirmation = true;

        // Se agrega la fecha de creacion del estado
        $affiliation->date_issue = date('Y-m-d');

        // Se almacena la solicitud de afiliacion para este usuario
        $user->affiliation_tec()->save($affiliation);

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitud de afiliación ha sido creada con éxito');
    }

    public function update(Request $request)
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Validamos que la solicitud no este aceptada o rechazada
        if ($user->affiliation_tec->state == 2) {
            return $this->sendResponse(message: 'El técnico no puede realizar cambios cuando la afiliación ya está aceptada');
        }

        // Validación de los datos de entrada
        $request->validate([
            'profession' => ['required', 'string', 'min:3', 'max:50'],
            'specialization' => ['required', 'string', 'min:3', 'max:50'],
            'work_phone' => ['required', 'numeric', 'digits:10'],
            'attention_schedule' => ['nullable', 'string', 'max:300'],
            'local_name' => ['nullable', 'string', 'max:50'],
            'local_address' => ['nullable', 'string', 'max:300'],
            'confirmation' => ['nullable', 'boolean'], //Dejamos en nullable TEMPORALMENTE
        ]);

        // Validamos si la confirmacion esta activa
        if ($request->confirmation == false) {
            return $this->sendResponse(message: 'El técnico tiene que aceptar los termino y condiciones');
        }

        // Se agrega la fecha de creacion del estado
        $request->date_issue = date('Y-m-d');

        // Se obtiene la affiliation de tencio autenticado
        $affiliation = $user->affiliation_tec;

        //Seteamos la confirmacion TEMPORALMENTE
        $affiliation->confirmation = true;

        // Se guardan los cambios en la base de datos
        $affiliation->update($request->all());

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitud de afiliación ha sido actualizada');
    }
}
