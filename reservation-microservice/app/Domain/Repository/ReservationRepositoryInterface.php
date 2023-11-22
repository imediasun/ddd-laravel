<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Reservation;

interface ReservationRepositoryInterface
{
    public function findById(string $id): Reservation;
    public function create(Reservation $reservation): void;
    public function update(Reservation $reservation): void;
}

