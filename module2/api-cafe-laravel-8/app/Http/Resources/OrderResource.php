<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'table' => $this->table->id,
            'shift_workers' => $this->worker->name,
            'create_at' => $this->created_at,
            'status' => $this->status,
            'price' => $this->priceAll()
        ];
    }
}
