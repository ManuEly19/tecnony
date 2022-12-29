<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HiringTecResource extends JsonResource
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
        return [
            'id' => $this->id,
            // respuesta generales
            'state' => $this->state,
            'diagnosis' => $this->diagnosis,

            // respuesta de datos de finalizacion del servicio
            'incident_resolution' => $this->incident_resolution,
            'warranty' => $this->warranty,
            'spare_parts' => $this->spare_parts,
            'price_spare_parts' => $this->price_spare_parts,
            'final_price' => $this->final_price,
            'end_date' => $this->end_date,
        ];
    }
}
