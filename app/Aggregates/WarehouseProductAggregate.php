<?php

namespace App\Aggregates;

use App\Dtos\ProductMovedDto;
use App\Events\ProductMoved;
use App\Exceptions\InsufficientAmountException;
use App\Models\Price;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class WarehouseProductAggregate extends AggregateRoot
{
    public function moveProduct(ProductMovedDto $eventDto): static {
        $price = Price::uuid($eventDto->priceUuid);

        if ($price->amount >= $eventDto->amount) {
            $this->recordThat(new ProductMoved($eventDto));
        } else {
            throw new InsufficientAmountException('Amount of product on the warehouse is insufficient.');
        }

        return $this;
    }

    public function applyProductMoved(ProductMoved $event)
    {
        // Do nothing ... ?
    }
}
