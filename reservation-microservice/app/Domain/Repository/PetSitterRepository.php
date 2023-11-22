<?php

namespace App\Domain\Repository;

use App\Domain\Entity\PetSitter;
use \App\Domain\Repository\PetSitterRepositoryInterface;
class PetSitterRepository implements PetSitterRepositoryInterface
{
    public function findById(string $id): PetSitter
    {
        return PetSitter::find($id);
    }

    public function create(PetSitter $sitter): void
    {
        $sitter->save();

    }

    public function update(PetSitter $sitter): void
    {
        $sitter->save();
    }
    public function getAvailablePetSitters(): array
    {
        // Example implementation fetching available pet sitters from a database
        return PetSitter::where('is_available', true)->get()->toArray();
    }
}
