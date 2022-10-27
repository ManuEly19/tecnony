<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliation_tec extends Model
{
    use HasFactory;

    // Relacion de uno a uno
    // Una solicitud de afiliación del lado del técnico tiene una solicitud de afiliación del lado del admin
    public function affiliation_ad ()
    {
        return $this->hasOne(Affiliation_ad::class);
    }
}
