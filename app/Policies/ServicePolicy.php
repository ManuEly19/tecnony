<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ServicePolicy
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

    // Determinar el permiso para el método show
    public function view(User $user, Service $service)
    {
        return $user->id === $service->user_id
            ? Response::allow()
            : Response::deny("You don't own this service.");
    }

    // Determinar el permiso para el método create
    public function create(User $user)
    {
        return $user->role->slug === "tecnico";
    }

    // Determinar el permiso para el método update
    public function update(User $user, Service $service)
    {
        return $user->id === $service->user_id
            ? Response::allow()
            : Response::deny("You don't own this service.");
    }

    // Determinar el permiso para el método delete
    public function delete(User $user, Service $service)
    {
        return $user->id === $service->user_id
            ? Response::allow()
            : Response::deny("You don't own this service.");
    }
}
