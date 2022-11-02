<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_request_cli extends Model
{
    use HasFactory;

    // Relacion de uno a uno
    // Una solicitud de servicio tiene un formulario de satisfacción
    public function satisfaction_form()
    {
        return $this->hasOne(Satisfaction_form::class);
    }

    // Relacion de uno a uno
    // Solicitud de servicio de lado del cliente tiene una solicitud de servicios del lado del técnico
    public function service_request_tec()
    {
        return $this->hasOne(Service_request_tec::class);
    }

    // Relación de uno a muchos
    // Una solicitud de servicios le pertenece a un servicio
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Relación de uno a muchos
    // una solicitud de servicio le pertenece a un usuario cliente
    public function user_cli()
    {
        return $this->belongsTo(User::class);
    }
}
