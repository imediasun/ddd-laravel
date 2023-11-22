<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Domain\Entity\PetOwner;
use App\Domain\Entity\PetSitter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        return DB::transaction(function () use ($input) {

            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'is_pet_sitter' => $input['is_pet_sitter'],
                'is_pet_owner' => $input['is_pet_owner'],
                'password' => Hash::make($input['password']),
            ]);

            if ($input['is_pet_sitter']) {
                $petSitter = PetSitter::create([
                    'user_id' => $user->id,
                    // Other fields for pet sitter entity
                ]);
            } elseif ($input['is_pet_owner']) {
                $petOwner = PetOwner::create([
                    'user_id' => $user->id,
                    // Other fields for pet owner entity
                ]);
            }
            return $user;
        });
    }
}
