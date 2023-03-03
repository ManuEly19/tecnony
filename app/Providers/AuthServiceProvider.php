<?php

namespace App\Providers;

use App\Models\AffiliationAd;
use App\Models\Service;
use App\Models\ServiceRequestCli;
use App\Models\ServiceRequestTec;
use App\Models\User;
use App\Policies\AffiliationAdPolicy;
use App\Policies\ApproveHiringPolicy;
use App\Policies\HiringPolicy;
use App\Policies\ServicePolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    // https://laravel.com/docs/9.x/authorization#registering-policies
    protected $policies = [
        AffiliationAd::class => AffiliationAdPolicy::class,
        Service::class => ServicePolicy::class,
        ServiceRequestCli::class => HiringPolicy::class,
        ServiceRequestTec::class => ApproveHiringPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // https://laravel.com/docs/9.x/authorization#writing-gates

        // -------- ADMINISTRADOR --------
        // 1 . Puede gestionar solicitudes de afiliacion
        Gate::define('manage-affiliations', function (User $user) {
            return $user->role->slug === "admin";
        });

        // De visualizar comentarios, sugerencias y calificaciones
        // 2. Puede activar o desactivar Tecnicos
        Gate::define('manage-tec-comment', function (User $user) {
            return $user->role->slug === "admin";
        });


        // -------- TECNICO --------
        // 1. Puede Solicitar afiliacion
        Gate::define('affiliation', function (User $user) {
            return $user->role->slug === "tecnico";
        });

        // 2. Puede gestionar servicios
        Gate::define('manage-services', function (User $user) {
            return $user->role->slug === "tecnico";
        });

        // 3. Aprobar servicios y [visualizar sus propias solicitudes]
        Gate::define('approve-hiring', function (User $user) {
            return $user->role->slug === "tecnico";
        });

        // De visualizar comentarios, sugerencias y calificaciones
        // []. Puede [visualizar los suyos ]
        Gate::define('view-satisfaction-form', function (User $user) {
            return $user->role->slug === "tecnico";
        });


        // -------- CLIENTE --------
        // 2. Contratar servicio
        Gate::define('hire-services', function (User $user) {
            return $user->role->slug === "cliente";
        });

        // 3. Gestionar las contrataciones o solicitudes de servicio
        Gate::define('manage-hiring', function (User $user) {
            return $user->role->slug === "cliente";
        });

        // De comentarios, sugerencias y calificaciones
        // 4.Hacer el formulario de satisfacion
        Gate::define('make-satisfaction-form', function (User $user) {
            return $user->role->slug === "cliente";
        });
    }
}