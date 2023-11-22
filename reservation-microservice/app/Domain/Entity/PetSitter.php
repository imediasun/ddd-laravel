<?php

namespace App\Domain\Entity;

use App\Domain\ValueObject\PetSitter\Address;
use App\Domain\ValueObject\PetSitter\Name;
use App\Domain\ValueObject\PetSitter\Phone;
use App\Domain\ValueObject\Id;

final class PetSitter
{
    private string $petSitterId;
    private array $bookings = []; // Assuming bookings are stored for the pet sitter


    public function __construct(
        private Name    $name,
        private Phone   $phone,
        private Address $address,
    )
    {
        $this->petSitterId = Id::create();
    }

    public function getId(): string
    {
        return $this->petSitterId;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }


    public function isAvailableAt(\DateTimeImmutable $startTime): bool
    {
        // Check if the pet sitter is available at the specified start time
        foreach ($this->bookings as $booking) {
            // Assuming bookings are stored as [start_time, end_time]
            [$bookingStartTime, $bookingEndTime] = $booking;

            // Check if the start time falls between any existing booking times
            if ($startTime >= $bookingStartTime && $startTime < $bookingEndTime) {
                return false; // Pet sitter is not available at this time
            }
        }

        return true; // Pet sitter is available at this time
    }

    // Method to add a booking for the pet sitter
    public function addBooking(\DateTimeImmutable $startTime, \DateTimeImmutable $endTime): void
    {
        $this->bookings[] = [$startTime, $endTime];
    }

}
