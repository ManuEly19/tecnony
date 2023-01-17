<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'state' => $this->state,

            'name' => $this->name,
            'categories' => $this->categories,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $this->getImagePath(),

            'attention_mode' => $this->attention_mode,
            'attention_description' => $this->attention_description,

            'payment_method' => $this->payment_method,
            'payment_description' => $this->payment_description,

            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
