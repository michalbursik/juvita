<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovementResource extends JsonResource
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
            'type' => $this->type,
            'translated_type' => $this->translated_type,
            'amount' => $this->amount,
            'price' => $this->price,
            'product' => new ProductResource($this->whenLoaded('product')),
            'issue_warehouse' => new WarehouseResource($this->whenLoaded('issueWarehouse')),
            'receipt_warehouse' => new WarehouseResource($this->whenLoaded('receiptWarehouse')),
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at,
        ];
    }
}
