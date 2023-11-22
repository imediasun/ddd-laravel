<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Reservation\Status;

final class Reservation
{
    private string $reservationId;

    private Status $status;

    private PetOwner $petOwner;

    private PetSitter $petSitter;

    private $startTime;
    private $endTime;

    private array $lineItems = [];

    public function __construct($petOwner,$petSitter,$startTime, $endTime) {
        $this->reservationId = Id::create();

        $this->petOwner = $petOwner;
        $this->petSitter = $petSitter;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    public function getId(): string
    {
        return $this->reservationId;
    }

    public function getPetSitter(): PetSitter
    {
        return $this->petSitter;
    }

    public function getPetOwner(): PetOwner
    {
        return $this->petOwner;
    }

    public function setLineItems($lineItem): void
    {
        $this->lineItems = $lineItem;
    }

    public function getLineItems(): array
    {
        return $this->lineItems;
    }

    public function changeStatus(Status $status = Status::NEW): void
    {
        $this->status = $status;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }
}
