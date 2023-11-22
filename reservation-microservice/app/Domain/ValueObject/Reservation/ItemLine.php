<?php

namespace App\Domain\ValueObject\Reservation;

use Illuminate\Support\Facades\Validator;

final readonly class ItemLine
{
    public function __construct(
        private Item $item,
        private Quantity $quantity,
    ) {
    }

    public function getItem(): Item
    {
        return $this->item;
    }

    public function getQuantity(): int
    {
        return $this->quantity->getQuantity();
    }
}
