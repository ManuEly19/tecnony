<?php

namespace App\Policies;

use App\Models\ServiceRequestTec;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ApproveHiringPolicy
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
        return $user->role->slug === "tecnico";
    }

    // Hay una funcion que trae las controtaciones a traves de servicio

    // Determinar el permiso para el método show
    public function view(User $user, ServiceRequestTec $service_resquest_tec)
    {
        return $user->id === $service_resquest_tec->user_id
            ? Response::allow()
            : Response::deny("You are not the owner of this service request.");
    }

    // Determinar el permiso para el método create
    public function create(User $user)
    {
        return $user->role->slug === "tecnico";
    }

/*     // Determinar el permiso para el método update
    public function update(User $user, ServiceRequestTec $service_resquest_tec)
    {
        return $user->id === $service_resquest_tec->user_id
            ? Response::allow()
            : Response::deny("You are not the owner of this service request.");
    } */

    // Determinar el permiso para el método delete
    public function delete(User $user, ServiceRequestTec $service_resquest_tec)
    {
        return $user->id === $service_resquest_tec->user_id
            ? Response::allow()
            : Response::deny("You are not the owner of this service request.");
    }
}
