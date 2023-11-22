<?php

namespace App\Domain\ValueObject\Reservation;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

final readonly class Reservation
{
    public function __construct(private int $quantity = 1)
    {
        $this->assertQuantityIsValid(['quantity' => $quantity]);
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    private function assertQuantityIsValid(array $value): void
    {
        $validator = Validator::make($value, [
            'quantity' => 'numeric|min:1|max:12',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors());
        }
    }
}
