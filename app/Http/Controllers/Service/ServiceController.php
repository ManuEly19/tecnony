<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function __construct()
    {
        // Uso del gate para que pueda gestionar servicios
        $this->middleware('can:manage-services');

        // https://laravel.com/docs/9.x/authorization#authorizing-resource-controllers
        $this->authorizeResource(Service::class, 'service');
    }

    // Listar los servicios del tecnico
    public function index()
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Validamos si existen servicios para el usuario
        if (!$user->services->first()) {
            return $this->sendResponse(message: 'No tienes ningún servicio, crea tu primer servicio');
        }

        // Del usuario se obtiene los servicios
        $services = $user->services;

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Lista de servicios generada con éxito', result: [
            'services' => ServiceResource::collection($services)
        ]);
    }

    // Crear un nuevo servicio
    public function create(Request $request)
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Validamos
        // * Si el tecnico esta activo
        // * Si el tecnico esta afiliado
        if (($user->state != 1) or ($user->affiliation_tec->state != 2)) {
            return $this->sendResponse(message: 'Esta acción no está autorizada, estas desactivado o no tienes afiliación vigente');
        }

        // Validación de los datos de entrada
        $request->validate([
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'categories' => ['nullable', 'string', 'min:5', 'max:50'],
            'description' => ['required', 'string', 'min:5', 'max:300'],
            'price' => ['required', 'numeric'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:1000'],
        ]);

        // Obtenemos el servicio con el mismo nombre que el de recien ingresado
        $ser = Service::where('user_id', $user->id)
        ->where('name', $request->name);

        // Validar que el servicio no se repita para el mismo usuario
        if ($ser->first() != null) {
            return $this->sendResponse(message: 'Ya existe este servicio');
        }

        // Del request se obtiene unicamente los dos campos
        $service_data = $request->only(['name','categories','description', 'price']);

        // Se crea una nueva instancia (en memoria)
        $service = new Service($service_data);

        // Del usuario se almacena su servicio en base a la relación
        $user->services()->save($service);

        // Si del request se tiene una imagen
        if ($request->has('image')) {
            // Pasando a la función la imagen del request
            $image = $request['image'];

            // Se guarda la imagen en Cloudinary
            $imageService = Cloudinary::upload($image->getRealPath(), ["Sercios" => "imageService"]);

            $direciones = $imageService->getSecurePath();

            // Se hace uso del Trait para asociar esta imagen con el modelo servicio
            $service->attachImage($direciones);

            // Actualizar el servicio
            $service->update();
        }

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'El servicio fue creado con éxito');
    }

    // Mostrar la información del reporte
    public function show(Service $service)
    {
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Detalles del servicio', result: [
            'service' => new ServiceResource($service),
        ]);
    }

    // Actualiza un servicio
    public function update(Request $request, Service $service)
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Validamos
        // * Si el tecnico esta activo
        // * Si el tecnico esta afiliado
        if (($user->state != 1) or ($user->affiliation_tec->state != 2)) {
            return $this->sendResponse(message: 'Esta acción no está autorizada, estas desactivado o no tienes afiliación vigente');
        }

        // Validación de los datos de entrada
        $request->validate([
            'name' => ['required', 'string', 'min:5', 'max:50'],
            'categories' => ['nullable', 'string', 'min:5', 'max:50'],
            'description' => ['required', 'string', 'min:5', 'max:300'],
            'price' => ['required', 'numeric'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:1000'],
        ]);

        // Del request se obtiene unicamente los dos campos
        $service_data = $request->only(['name','categories','description', 'price']);

        // Si del request se tiene una imagen
        if ($request->has('image')) {
            // Pasando a la función la imagen del request
            $image = $request['image'];
            
            // Se guarda la imagen en Cloudinary
            $imageService = Cloudinary::upload($image->getRealPath(), ["Sercios" => "imageService"]);

            $direciones = $imageService->getSecurePath();

            // Se hace uso del Trait para asociar esta imagen con el modelo servicio
            $service->attachImage($direciones);
        }

        // Se actualiza el servicio
        $service->fill($service_data)->save();

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Servicio actualizado con éxito');
    }

    // Dar de baja a un Servicio
    public function destroy(Service $service)
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Validamos
        // * Si el tecnico esta activo
        // * Si el tecnico esta afiliado
        if (($user->state != 1) or ($user->affiliation_tec->state != 2)) {
            return $this->sendResponse(message: 'Esta acción no está autorizada, estas desactivado o no tienes afiliación vigente');
        }

        // Obtener el estado del servicio
        $service_state = $service->state;

        // Almacenar un string con el mensaje
        $message = $service_state ? 'desactivado ' : 'activado';

        // Cambia el estado al servicio
        $service->state = !$service_state;
        // Guardar en la BDD
        $service->save();
        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: "El servicio fue $message con éxito");
    }
}
