<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_request_cli extends Model
{
    use HasFactory;

    // Relacion de uno a uno
    // Una solicitud de servicio tiene un formulario de satisfacción
    public function satisfaction_form ()
    {
        return $this->hasOne(Satisfaction_form::class);
    }

    // Solicitud de servicio de lado del cliente tiene una solicitud de servicios del lado del técnico
    public function service_request_tec ()
    {
        return $this->hasOne(Service_request_tec::class);
    }
}
