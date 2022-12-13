<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\SatisfactionForm;
use App\Models\ServiceRequestTec;
use Illuminate\Support\Facades\Auth;

class ViewCommentController extends Controller
{
    // Creacion del consturctor
    public function __construct()
    {
        // Uso del gate para que pueda ver los comentarios
        $this->middleware('can:view-satisfaction-form');
    }

    // Mostrar los comentarios, sugerincias y calificaciones del tecnico
    public function index()
    {
        // Se obtiene el usuario autenticado
        $user = Auth::user();

        // Se obtiene el id del usario autenticado
        $userid = $user->id;

        // Obtenemos los id de las solicitudes de servicios del atendidas por el tecnico
        $serviceid = ServiceRequestTec::select('service_request_cli_id')
            ->where('user_id', $userid)->get();

        // obtenemos los fomularios de satisfacion hechas para el tecnico
        $comment = SatisfactionForm::whereIn('service_request_cli_id', $serviceid)->get();

        // Validamos si existen solicitudes para este tecnico
        if (!$comment->first()) {
            return $this->sendResponse(message: 'Usted no tiene comentarios');
        }

        // Obtenemos el promedio de la calificacion
        $score = round($comment->avg('qualification'), 2);

        // Invoca el controlador padre para la respuesta json
        return $this->sendResponse(message: 'Las solicitudes de servicios comentados se han devuelto con éxito', result: [
            'score' => $score,
            'satisfaction_forms' => CommentResource::collection($comment)
        ]);
    }
}
