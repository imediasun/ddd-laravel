<?php

namespace App\Domain\ValueObject\Reservation;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

final readonly class ItemName
{
    public function __construct(public string $name) {
        $this->assertNameIsValid(['name' => $this->name]);
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function assertNameIsValid(array $value): void
    {
        $validator = Validator::make($value, [
            'name' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors());
        }
    }
}
