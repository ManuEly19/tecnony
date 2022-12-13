<?php

namespace App\Policies;

use App\Models\AffiliationAd;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class AffiliationAdPolicy
{
    use HandlesAuthorization;

    // Determinar el permiso para el método index
    public function viewAny(User $user)
    {
        return $user->role->slug === "admin";
    }

    // Determinar el permiso para el método show
    public function view(User $user, AffiliationAd $affiliationAd)
    {
        return $user->id === $affiliationAd->user_id
            ? Response::allow()
            : Response::deny("Usted no es responsable de esta afiliación");
    }

    // Determinar el permiso para el método create
    public function create(User $user)
    {
        return $user->role->slug === "admin";
    }

    // Determinar el permiso para el método create
    public function update(User $user, AffiliationAd $affiliationAd)
    {
        return $user->id === $affiliationAd->user_id
            ? Response::allow()
            : Response::deny("Usted no es responsable de esta afiliación");
    }
}
