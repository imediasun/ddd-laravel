<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\PetOwner\Address;
use App\Domain\ValueObject\PetOwner\Name;
use App\Domain\ValueObject\PetOwner\Phone;
use App\Domain\ValueObject\Id;

final class PetOwner
{
    private string $clientId;

    public function __construct(
        private Name    $name,
        private Phone   $phone,
        private Address $address,
    )
    {
        $this->ownerId = Id::create();
    }

    public function getId(): string
    {
        return $this->ownerId;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }
}
