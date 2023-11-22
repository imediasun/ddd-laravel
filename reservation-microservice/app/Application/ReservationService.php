<?php

namespace App\Application;

use App\Domain\Entity\Reservation;
use App\Domain\Repository\PetOwnerRepositoryInterface;
use App\Domain\Repository\PetSitterRepositoryInterface;
use App\Domain\Repository\ReservationRepositoryInterface;
use App\Domain\ValueObject\Reservation\Status;

readonly class ReservationService
{
    public function __construct(
        private PetOwnerRepositoryInterface $petOwnerRepository,
        private PetSitterRepositoryInterface $petSitterRepository,
        private ReservationRepositoryInterface $reservationRepository,
    ) {
    }

    public function reservePetSitter(string $petOwnerId, string $petSitterId, \DateTimeImmutable $startTime): string
    {
        $petOwner = $this->petOwnerRepository->findById($petOwnerId);
        $petSitter = $this->petSitterRepository->findById($petSitterId);

        // Validate if the pet sitter is available at the specified start time
        if (!$petSitter->isAvailableAt($startTime)) {
            throw new \Exception('Pet sitter is not available at the specified time.');
        }

        // Calculate end time for the reservation (24 hours for dog boarding)
        $endTime = $startTime->add(new \DateInterval('P1D'));

        $reservation = new Reservation($petOwner, $petSitter, $startTime, $endTime);
        $reservation->changeStatus(Status::PROCESSING);

        $this->reservationRepository->create($reservation);

        return $reservation->getId();
    }

    public function listAvailablePetSitters(): array
    {
        return $this->petSitterRepository->getAvailablePetSitters();
    }
}
