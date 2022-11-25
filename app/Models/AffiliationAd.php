<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliationAd extends Model
{
    protected $guarded = [];

    use HasFactory;

    // Relacion de uno a uno
    // Una solicitud de afiliación del lado del admin le pertenece a una solicitud de afiliación del lado del técnico.
    public function affiliation_tec()
    {
        return $this->belongsTo(AffiliationTec::class);
    }

    // Relacion de uno a muchos
    // Una solicitud de afiliación es gestionada por un admin.
    public function user_ad()
    {
        return $this->belongsTo(User::class);
    }

}
