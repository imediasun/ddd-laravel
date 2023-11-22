<?php

namespace App\Domain\Repository;

use App\Domain\Entity\PetSitter;

interface PetSitterRepositoryInterface
{
    public function findById(string $id): PetSitter;
    public function create(PetSitter $sitter): void;
    public function update(PetSitter $sitter): void;
    public function getAvailablePetSitters(): array;
}
