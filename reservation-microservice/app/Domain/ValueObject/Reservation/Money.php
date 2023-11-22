<?php

namespace App\Domain\ValueObject\Reservation;

use App\Domain\ValueObject\Currency;

final readonly class Money
{
    public function __construct(
        private float $amount,
        private Currency $curr,
    ) {
    }

    public function getName(): string
    {
        return $this->curr->name;
    }

    public function getValue(): string
    {
        return $this->curr->value;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
