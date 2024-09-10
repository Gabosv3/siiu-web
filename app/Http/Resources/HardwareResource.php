<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HardwareResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'categoria_id' => $this->categoria_id,
            'conflictos' => $this->conflictos,
            'estado' => $this->estado,
            'user_id' => $this->user_id,
            'ubicacion_id' => $this->ubicacion_id,
            'codigo_de_inventario' => $this->codigo_de_inventario,
            'numero_de_serie' => $this->numero_de_serie,
            'fabricante_id' => $this->fabricante_id,
            'modelo_id' => $this->modelo_id,
            'sistemas_asignados' => $this->sistemas_asignados,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
