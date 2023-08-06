<?php

namespace App\Projectors;

use App\Events\DiscountCreated;
use App\Events\DiscountModified;
use App\Events\DiscountRemoved;
use App\Models\Discount;
use App\Repositories\DiscountRepository;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class DiscountProjector extends Projector
{
    public function __construct(
        private readonly DiscountRepository $discountRepository
    ) {}

    public function onDiscountCreated(DiscountCreated $event): void
    {
        $this->discountRepository->store($event->discountAttributes);
    }
    public function onDiscountModified(DiscountModified $event): void
    {
        $discount = Discount::uuid($event->discountUuid);

        $this->discountRepository->update($discount, $event->discountAttributes);
    }
    public function onDiscountRemoved(DiscountRemoved $event): void
    {
        $discount = Discount::uuid($event->discountUuid);

        $this->discountRepository->destroy($discount);
    }
}
