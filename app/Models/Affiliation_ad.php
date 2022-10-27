<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliation_ad extends Model
{
    use HasFactory;

    // Relacion de uno a uno
    // Una solicitud de afiliación del lado del admin le pertenece a una solicitud de afiliación del lado del técnico.
    public function affiliation_tec ()
    {
        return $this->belongsTo(Affiliation_tec::class);
    }
}
