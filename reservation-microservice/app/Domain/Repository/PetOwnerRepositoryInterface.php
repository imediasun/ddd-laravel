<?php

namespace App\Domain\Repository;

use App\Domain\Entity\PetOwner;

interface PetOwnerRepositoryInterface
{
    public function findById(string $id): PetOwner;
    public function create(PetOwner $sitter): void;
    public function update(PetOwner $sitter): void;
}
