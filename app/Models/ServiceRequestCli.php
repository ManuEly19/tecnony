<?php

namespace App\Models;

use App\Trait\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequestCli extends Model
{
    protected $guarded = [];

    use HasFactory, HasImage;

    // Relacion de uno a uno
    // Una solicitud de servicio tiene un formulario de satisfacción
    public function satisfaction_form()
    {
        return $this->hasOne(SatisfactionForm::class);
    }

    // Relacion de uno a uno
    // Solicitud de servicio de lado del cliente tiene una solicitud de servicios del lado del técnico
    public function service_request_tec()
    {
        return $this->hasOne(ServiceRequestTec::class);
    }

    // Relación de uno a muchos
    // Una solicitud de servicios le pertenece a un servicio
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Relación de uno a muchos
    // una solicitud de servicio le pertenece a un usuario cliente
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación polimórfica uno a uno
    // Una solicitud de servicio pueden tener una imagen del comprovante del pago
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    //Obtener la imagen de la BD
    public function getImagePath()
    {
        // se verifica no si existe una iamgen
        if (!$this->image) {
            // asignarle el path de una imagen por defecto
            return 'No existe un comprovante de pago';
        }
        // retornar el path de la imagen registrada en la BDD
        return $this->image->path;
    }
}
