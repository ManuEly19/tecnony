<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AffiliationAdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Se procede a definir la estructura de la respuesta de la petición
        // https://laravel.com/docs/9.x/eloquent-resources#introduction
        return [
            'id' => $this->id,
            // nombre del tecnico
            'tecnico' => $this->affiliation_tec->user->getFullName(),
            // respuesta de datos de gestion del admin
            'state' => $this->state,
            'date_acceptance' => $this->date_acceptance,
            //  Respuesta de datos de control
            'observation' => $this->observation,
        ];
    }
}

