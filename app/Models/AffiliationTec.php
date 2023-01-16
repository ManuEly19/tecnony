<?php

namespace App\Models;

use App\Trait\HasFile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliationTec extends Model
{
    use HasFactory, HasFile;

    protected $fillable = ['state', 'date_issue', 'profession', 'specialization', 'work_phone','attention_schedule','local_name', 'local_address', 'confirmation', 'account_number', 'account_type', 'banking_entity'];

    // Relacion de uno a uno
    // Una solicitud de afiliación del lado del técnico tiene una solicitud de afiliación del lado del admin
    public function affiliation_ad()
    {
        return $this->hasOne(AffiliationAd::class);
    }

    // Relacion de uno a uno
    // Una solicitud de afiliación le pertenece a un usuario técnico
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación polimórfica uno a uno
    // Una solicitud de afiliacion de un tecnico pueden tener un archivo
    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    //Obtener el archivo de la BD
    public function getFilePath()
    {
        // se verifica no si existe un archivo
        if (!$this->file) {
            // asignarle el path de una imagen por defecto
            return 'No tiene archivo';
        }
        // retornar el path del archivo registrado en la BD
        return $this->file->path;
    }
}
