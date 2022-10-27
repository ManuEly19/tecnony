<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service_request_tec extends Model
{
    use HasFactory;

    // Relacion de uno a uno
    // Una solicitud de servicios del lado del técnico le pertenece a una solicitud de servicio del lado del cliente.
    public function service_request_cli ()
    {
        return $this->belongsTo(Service_request_cli::class);
    }
}
