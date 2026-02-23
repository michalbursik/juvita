<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PriceLevelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'price' => $this->price,
            'validFrom' => $this->validFrom,
            'validTo' => $this->validTo,
            'status' => $this->status,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
