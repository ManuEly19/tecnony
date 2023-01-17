<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HiringCliResource extends JsonResource
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
            'date_issue' => $this->date_issue,

            // respuesta de datos del dispositivo
            'device' => $this->device,
            'model' => $this->model,
            'brand' => $this->brand,
            'serie' => $this->serie,
            'description_problem' => $this->description_problem,
            'payment_method' => $this->payment_method
        ];
    }
}
