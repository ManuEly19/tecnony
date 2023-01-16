<?php

namespace App\Http\Controllers\Affiliation;

use App\Http\Controllers\Controller;
use App\Http\Resources\AffiliationAdResource;
use App\Http\Resources\AffiliationTecResource;
use App\Http\Resources\ProfileResource;
use App\Models\AffiliationAd;
use App\Models\AffiliationTec;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
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
        // * aceptacion o rechazo del admin, o en proceso de reatencionn
        // * Que los estados de la afiliacion es igual del lado del admin y tecnico
        if (($affiliation->state == 2 || $affiliation->state == 3 || $affiliation->state == 4) && $affiliation->state == $affiliation->affiliation_ad->state) {
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
            'confirmation' => ['required', 'boolean'],

            'account_number' => ['required', 'numeric', 'digits:10'],
            'account_type' => ['required', 'string', 'max:20'],
            'banking_entity' => ['required', 'string', 'max:50'],
            'documento' => ['required', 'file', 'mimes:pdf', 'max:5000'],
        ]);

        // Validamos si la confirmacion esta activa
        if ($request->confirmation == false) {
            return $this->sendResponse(message: 'El técnico tiene que aceptar los termino y condiciones');
        }

        // Del request se obtiene unicamente los dos campos
        $affiliation_data = $request->only(['profession', 'specialization', 'work_phone', 'attention_schedule', 'local_name', 'local_address', 'confirmation', 'account_number', 'account_type', 'banking_entity']);

        // Se crea instancia del la solicitud de afiliacion
        $affiliation = new AffiliationTec($affiliation_data);

        // Se asigana el estado de la solicitud
        $affiliation->state = 1;

        // Se agrega la fecha de creacion del estado
        $affiliation->date_issue = date('Y-m-d');

        // Se almacena la solicitud de afiliacion para este usuario
        $user->affiliation_tec()->save($affiliation);

        // Si del request se tiene una archivo
        if ($request->has('documento')) {
            // Pasando a la función del file del request
            $documento = $request['documento'];

            // Se guarda el archivo en Cloudinary
            $fileAffiliation = Cloudinary::upload($documento->getRealPath(), ["Afiliaciones" => "fileAffiliation"]);

            $direciones = $fileAffiliation->getSecurePath();

            // Se hace uso del Trait para asociar este archivo con el modelo affiliationTec
            $affiliation->attachFile($direciones);

            //Actualizar la afiliacion
            $affiliation->save();
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitud de afiliación ha sido creada con éxito');
    }

    public function update(Request $request)
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Validamos que la solicitud no este aceptada
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
            'confirmation' => ['required', 'boolean'],

            'account_number' => ['required', 'numeric', 'digits:10'],
            'account_type' => ['required', 'string', 'max:20'],
            'banking_entity' => ['required', 'string', 'max:50'],
            'documento' => ['nullable', 'file', 'mimes:pdf', 'max:5000'],
        ]);

        // Validamos si la confirmacion esta activa
        if ($request->confirmation == false) {
            return $this->sendResponse(message: 'El técnico tiene que aceptar los termino y condiciones');
        }

        // Del request se obtiene unicamente los dos campos
        $affiliation_data = $request->only(['profession', 'specialization', 'work_phone', 'attention_schedule','local_name', 'local_address', 'confirmation', 'account_number', 'account_type', 'banking_entity']);

        // Se obtiene la affiliation de tencio autenticado
        $affiliation = $user->affiliation_tec;

        // Se agrega la fecha de creacion del estado
        $affiliation->date_issue = date('Y-m-d');

        // Se establece la reatencion en caso de ser rechazado.
        if ($affiliation->state == 3) {
            $affiliation->state = 4;
            //actualizamos la afiliacion del lado del servidor
            $affiliation_ad = AffiliationAd::where('affiliation_tec_id', $affiliation->id)->first();
            $affiliation_ad->state = 4;
            $affiliation_ad->update();
        }

        // Si del request se tiene un archivo
        if ($request->has('documento')) {
            // Pasando a la función del file del request
            $documento = $request['documento'];

            // Se guarda el archivo en Cloudinary
            $fileAffiliation = Cloudinary::upload($documento->getRealPath(), ["Afiliaciones" => "fileAffiliation"]);

            $direciones = $fileAffiliation->getSecurePath();

            // Se hace uso del Trait para asociar este archivo con el modelo affiliationTec
            $affiliation->attachFile($direciones);

            //Actualizar la afiliacion
            $affiliation->save();
        }

        // Se guardan los cambios en la base de datos
        $affiliation->fill($affiliation_data)->save();

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'La solicitud de afiliación ha sido actualizada');
    }
}
