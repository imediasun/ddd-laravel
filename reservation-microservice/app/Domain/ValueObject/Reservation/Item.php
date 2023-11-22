<?php

namespace App\Domain\ValueObject\Reservation;

use App\Domain\ValueObject\Id;

final class Item
{
    private string $itemId;
    private ?string $description = null;

    public function __construct(
        private readonly ItemName $name,
        private readonly Money $price,
    ) {
        $this->itemId = Id::create();
    }

    public function getId(): string
    {
        return $this->itemId;
    }

    public function getName(): ItemName
    {
        return $this->name;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }
}
