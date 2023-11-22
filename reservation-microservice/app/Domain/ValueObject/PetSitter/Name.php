<?php

namespace App\Domain\ValueObject\PetSitter;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

final readonly class Name
{
    public function __construct(
        private string $firstName,
        private string $lastName,
    ) {
        $this->assertNameIsValid([
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]);
    }

    public function getFirst(): string
    {
        return $this->firstName;
    }

    public function getLast(): string
    {
        return $this->lastName;
    }

    public function getFull(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    private function assertNameIsValid(array $value): void
    {
        $validator = Validator::make($value, [
            'firstName' => 'required|min:2',
            'lastName' => 'required|min:2',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors());
        }
    }
}
