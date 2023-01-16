<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded = [];

    use HasFactory;

    // Relación polimórfica uno a uno
    // Un archivo le pertenece a una solicitud de afiliacion de un tecnico.
    public function imageable()
    {
        return $this->morphTo();
    }
}
