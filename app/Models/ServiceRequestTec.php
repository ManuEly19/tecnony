<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequestTec extends Model
{
    protected $fillable = ['diagnosis', 'incident_resolution', 'warranty', 'spare_parts', 'price_spare_parts', 'final_price'];

    use HasFactory;

    // Relacion de uno a uno
    // Una solicitud de servicios del lado del técnico le pertenece a una solicitud de servicio del lado del cliente.
    public function service_request_cli()
    {
        return $this->belongsTo(ServiceRequestCli::class);
    }

    // Relación de uno a muchos
    // Una solicitud de servicio es atendido por un usuario técnico
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
