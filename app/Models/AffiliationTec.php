<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliationTec extends Model
{
    protected $guarded = [];

    use HasFactory;

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
}
