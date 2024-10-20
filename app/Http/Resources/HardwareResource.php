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
            'category' => new CategoryResource($this->whenLoaded('category')),  // Incluye toda la categoría
            'conflicts' => $this->conflicts,
            'status' => $this->status,
            'user_id' => $this->user_id,
            'departament_id' => $this->departament_id,
            'inventory_code' => $this->inventory_code,
            'serial_number' => $this->serial_number,
            'manufacturer_id' => $this->manufacturer_id,
            'model_id' => $this->model_id,
            'warranty_expiration_date' => $this->warranty_expiration_date,
            'barcode_path' => $this->barcode_path,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
