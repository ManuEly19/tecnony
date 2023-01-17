<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AffiliationTecResource extends JsonResource
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
            // respuesta generales
            'state' => $this->state,
            'date_issue' => $this->date_issue,
            // Propietario de la solicitu
            'full_name' => $this->user->first_name .' '. $this->user->last_name,
            //  Respuesta de datos laborales del tecnico
            'profession' => $this->profession,
            'specialization' => $this->specialization,
            'work_phone' => $this->work_phone,
            'whatsapp' => 'wa.me/593' . $this->work_phone,
            'attention_schedule' => $this->attention_schedule,
            'local_name' => $this->local_name,
            'local_address' => $this->local_address,
            'confirmation' => $this->confirmation,
            // Datos Bancarios
            'account_number' => $this->account_number,
            'account_type' => $this->account_type,
            'banking_entity' => $this->banking_entity,
            // Archivo
            'documento' => $this->getFilePath(),
        ];
    }
}
