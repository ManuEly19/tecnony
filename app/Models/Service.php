<?php

namespace App\Models;

use App\Trait\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name','categories','description', 'price'];

    use HasFactory, HasImage;

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

    // Crear un Imagen por default
    public function getDefaultImagePath()
    {
        return "https://img.freepik.com/vector-premium/reparacion-movil-ilustracion-dibujos-animados-servicio-electronica-telefono-o-telefono-inteligente_2175-5064.jpg?w=996";
    }

    //Obtener la imagen de la BD
    public function getImagePath()
    {
        // se verifica no si existe una iamgen
        if (!$this->image) {
            // asignarle el path de una imagen por defecto
            return $this->getDefaultImagePath();
        }
        // retornar el path de la imagen registrada en la BDD
        return $this->image->path;
    }
}
