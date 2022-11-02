<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relación de uno a muchos
    // Un usuario le pertenece un rol
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Relación de uno a muchos
    // Un usuario tecnico puede proporcionar muchos servicios
    public function services()
    {
        return $this->hasMany(Service::class);
    }

    // Relacion de uno a uno
    // Un usuario tecnico tiene una solicitud de afiliacion
    public function affiliation_tec()
    {
        return $this->hasOne(Affiliation_tec::class);
    }

    // Relación de uno a muchos
    // Un usuario admin tiene que gestionar muchas solicitudes de afiliación
    public function affiliation_ad()
    {
        return $this->hasMany(Affiliation_ad::class);
    }

    // Relación de uno a muchos
    // Un usuario cliente hace de uno a muchas solicitudes de servicio
    public function service_request_cli()
    {
        return $this->hasMany(Service_request_cli::class);
    }

    // Relación de uno a muchos
    // Un usuario técnico atiende de uno a muchas solicitudes de servicios
    public function service_request_tec()
    {
        return $this->hasMany(Service_request_tec::class);
    }
}
