<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\SatisfactionForm;
use App\Models\ServiceRequestTec;
use App\Models\User;
use App\Notifications\TecnicoDeshabilitadoNotifi;
use App\Notifications\TecnicoHabilitadoNotifi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageTecCommentController extends Controller
{
    // Creacion de contructor
    public function __construct()
    {
        // Uso del gate para que pueda ver los comentarios
        $this->middleware('can:manage-tec-comment');
    }

    // Mostrar tecnicos
    public function index()
    {
        // Obtener el rol del usuario
        $role = Role::where('slug', 'tecnico')->first();
        // Obtener los usuarios en base a la relación
        $users = $role->users;

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Technician list generated successfully', result: [
            'users' => UserResource::collection($users),
        ]);
    }

    // Mostrar comentarios y puntuacion de tecnico selecionado
    public function show(User $tec)
    {
        // validamos si el usuario es tecnico
        if ($tec->role_id != 2) {
            return $this->sendResponse(message: 'This action is unauthorized.');
        }

        // Obtenemos los id de las solicitudes de servicios del atendidas por el tecnico
        $serviceid = ServiceRequestTec::select('service_request_cli_id')
            ->where('user_id', $tec->id)->get();

        // Obtenemos los fomularios de satisfacion hechas para el tecnico
        $comment = SatisfactionForm::whereIn('service_request_cli_id', $serviceid)->get();

        // Validamos si existen solicitudes para este tecnico
        if (!$comment->first()) {
            return $this->sendResponse(message: 'This technician has no comments');
        }

        // Obtenemos el promedio de la calificacion
        $score = round($comment->avg('qualification'), 2);

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'The service request to comment was returned successfully', result: [
            'score' => $score,
            'technical' => new ProfileResource($tec),
            'satisfaction_forms' => CommentResource::collection($comment)
        ]);
    }

    // Activar o desactivar tecnico selecionado
    public function change(Request $request, User $tec)
    {
        // validamos si el usuario es tecnico
        if ($tec->role_id != 2) {
            return $this->sendResponse(message: 'This action is unauthorized.' . $user->role_id);
        }

        // Obtiene el estado del usuario
        $user_state = $tec->state;

        // Crear un mensaje en base al estado del usuario
        $message = $user_state ? 'inactivated' : 'activated';

        // Cambiar el estado
        $tec->state = !$user_state;

        // Se obtiene el usuario autenticado
        $userad = Auth::user();

        // Validamos si existen solicitudes para este tecnico
        if ($tec->state == 0) {
            // Validacion de datos de entrada
            $request->validate([
                'observation' => ['required', 'string', 'min:5', 'max:500']
            ]);

            // Llamamos la notificacion
            $this->TecDesNotifi($tec, $request->observation, $userad->email, $userad->personal_phone);
        }

        // Validamos si existen solicitudes para este tecnico
        if ($tec->state == 1) {
            // Llamamos a la notificacion
            $this->TecHabNotifi($tec);
        }

        // Guardar en la BDD
        $tec->save();

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: "User $message successfully");
    }

    // Función para notificar el tecnico que ha sido
    private function TecDesNotifi(User $user, string $observation, string $email, int $personal_phone)
    {
        $user->notify(
            new TecnicoDeshabilitadoNotifi(
                user_name: $user->getFullName(),
                observation: $observation,
                email: $email,
                personal_phone: $personal_phone
            )
        );
    }

    // Función para notificar el tecnico que ha sido
    private function TecHabNotifi(User $user)
    {
        $user->notify(
            new TecnicoHabilitadoNotifi(
                user_name: $user->getFullName()
            )
        );
    }
}
