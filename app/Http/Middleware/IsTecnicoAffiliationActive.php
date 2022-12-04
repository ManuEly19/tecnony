<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsTecnicoAffiliationActive
{
    // Se procede a crear la lógica del middleware para
    // validar si el tecnico tiene la afiliacion aprobada
    public function handle(Request $request, Closure $next)
    {
        // Se obtiene la ruta que gestiona la petición
        $user = $request->route('user');
        // Si el usuario no esta activo
        if ($user->affiliation_tec->state != 2) {
            return abort(403, 'This action is unauthorized.');
        }
        // Si el usuario esta activo pasa a realizar las siguientes acciones
        return $next($request);
    }
}
