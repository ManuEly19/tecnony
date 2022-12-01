<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Trait\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasImage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email', 'username', 'first_name', 'last_name', 'cedula', 'personal_phone', 'home_phone',
        'address', 'password', 'birthdate',
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

    // RELACIONES
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
        return $this->hasOne(AffiliationTec::class);
    }

    // Relación de uno a muchos
    // Un usuario admin tiene que gestionar muchas solicitudes de afiliación
    public function affiliation_ad()
    {
        return $this->hasMany(AffiliationAd::class);
    }

    // Relación de uno a muchos
    // Un usuario cliente hace de uno a muchas solicitudes de servicio
    public function service_request_cli()
    {
        return $this->hasMany(ServiceRequestCli::class);
    }

    // Relación de uno a muchos
    // Un usuario técnico atiende de uno a muchas solicitudes de servicios
    public function service_request_tec()
    {
        return $this->hasMany(ServiceRequestTec::class);
    }

    // Relación polimórfica uno a uno
    // Un usuario pueden tener una imagen
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    // Obtener el nombre completo del usuario
    public function getFullName()
    {
        return "$this->first_name $this->last_name";
    }

    // Crear un avatar por default
    public function getDefaultAvatarPath()
    {
        return "https://cdn-icons-png.flaticon.com/512/848/848006.png";
    }

    // Obtener la imagen de la BDD
    public function getAvatarPath()
    {
        // se verifica no si existe una iamgen
        if (!$this->image) {
            // asignarle el path de una imagen por defecto
            return $this->getDefaultAvatarPath();
        }
        // retornar el path de la imagen registrada en la BDD
        return $this->image->path;
    }

    // Función para saber si el rol que tiene asignado el usuario
    // es el mismo que se le esta pasando a la función
    // https://laravel.com/docs/9.x/eloquent-relationships#one-to-many
    public function hasRole(string $role_slug)
    {
        return $this->role->slug === $role_slug;
    }
}
