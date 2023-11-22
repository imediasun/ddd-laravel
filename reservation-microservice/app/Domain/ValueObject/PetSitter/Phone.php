<?php

namespace App\Domain\ValueObject\PetSitter;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

final readonly class Phone
{
    public function __construct(private string $number)
    {
        $this->assertPhoneNumberIsValid(['number' => $number]);
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    private function assertPhoneNumberIsValid(array $value): void
    {
        $validator = Validator::make(
            $value,
            ['number' => 'regex:/^\+\d{12}$/i'],
            ['number' => 'The phone :attribute must be formatted +380660501234567']
        );

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors());
        }
    }
}
