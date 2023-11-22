<?php

namespace App\Domain\ValueObject\PetOwner;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

final class Address
{
    private ?string $zip = null;

    public function __construct(
        private readonly string $country,
        private readonly string $city,
        private readonly string $street,
    ) {
        $this->assertAddressIsValid([
            'country' => $country,
            'city' => $city,
            'street' => $street,
        ]);
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setZip(?string $zip): void
    {
        $this->zip = $zip;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function assertAddressIsValid(array $value): void
    {
        $validator = Validator::make($value, [
            'country' => [
                'required',
                Rule::in(['Украина', 'Польша'])
            ],
            'city' => [
                'required',
                Rule::in(['Киев', 'Варшава'])
            ],
            'street' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException($validator->errors());
        }
    }
}
