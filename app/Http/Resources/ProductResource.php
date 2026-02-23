<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'origin' => $this->origin,
            'active' => $this->active,
            'order' => $this->order,
            'unit' => $this->unit,
            'image' => $this->image,
            'amount' => null,
            'price' => null,
        ];

        if ($this->product_warehouse) {
            $data['amount'] = $this->product_warehouse->amount;
            $data['price'] = $this->product_warehouse->price;
        }

        if ($this->product_check) {
            $data['amount_before'] = $this->product_check->amount_before;
            $data['amount_after'] = $this->product_check->amount_after;
            $data['price_level_id'] = $this->product_check->price_level_id;
            $data['price'] = $this->product_check->price;
        }

        return $data;
    }
}
