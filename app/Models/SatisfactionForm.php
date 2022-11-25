<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SatisfactionForm extends Model
{
    protected $guarded = [];

    use HasFactory;

    // Relacion de uno a uno
    // Un formulario de satisfaction le pertenece a una solicitud de servicio
    public function service_request()
    {
        return $this->belongsTo(ServiceRequestCli::class);
    }
}
