<?php

namespace App\Policies;

use App\Models\ServiceRequestCli;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class HiringPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    // Determinar el permiso para el método index
    public function viewAny(User $user)
    {
        return $user->role->slug === "cliente";
    }

    // Determinar el permiso para el método show
    public function view(User $user, ServiceRequestCli $service_resquest_cli)
    {
        return $user->id === $service_resquest_cli->user_id
            ? Response::allow()
            : Response::deny("Usted no es el propietario de esta solicitud de servicio");
    }

    // Determinar el permiso para el método create
    public function create(User $user)
    {
        return $user->role->slug === "cliente";
    }

    // Determinar el permiso para el método update
    public function update(User $user, ServiceRequestCli $service_resquest_cli)
    {
        return $user->id === $service_resquest_cli->user_id
            ? Response::allow()
            : Response::deny("Usted no es el propietario de esta solicitud de servicio");
    }

    // Determinar el permiso para el método delete
    public function delete(User $user, ServiceRequestCli $service_resquest_cli)
    {
        return $user->id === $service_resquest_cli->user_id
            ? Response::allow()
            : Response::deny("Usted no es el propietario de esta solicitud de servicio");
    }

}
