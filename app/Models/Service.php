<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [];

    use HasFactory;

    // Relación de uno a muchos
    // Un servicio le pertenece a un usuario Tecnico
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación de uno a muchos
    // Un servicio tiene de uno a muchos solicitudes de servicio
    public function service_request()
    {
        return $this->hasMany(ServiceRequestCli::class);
    }

    // Relación polimórfica uno a uno
    // Un servicio pueden tener una imagen
    public function image()
    {
        return $this->morphOne(Image::class,'imageable');
    }
}
