<?php

namespace App\DTOs;

class ProductDTO
{
    public function __construct(
        public string $name,
        public ?string $origin,
        public int $order,
        public bool $active,
        public string $unit,
        public ?string $image = null,
    ) {}

    public static function fromRequest(array $validated): self
    {
        return new self(
            name: $validated['name'],
            origin: $validated['origin'] ?? null,
            order: (int) $validated['order'],
            active: (bool) $validated['active'],
            unit: $validated['unit'],
            image: $validated['image'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'origin' => $this->origin,
            'order' => $this->order,
            'active' => $this->active,
            'unit' => $this->unit,
            'image' => $this->image,
        ];
    }
}
