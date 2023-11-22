<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Validator;
use App\Application\ReservationService;
use Illuminate\Validation\ValidationException;

class ReserveSitter
{

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     * @throws ValidationException
     */

    private ReservationService $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }
    public function reserveSitter(array $input)
    {
        Validator::make($input, [
            'pet_sitter_id' => ['required', 'exists:pet_sitters,id'],
            'pet_owner_id' => ['required', 'exists:pet_owners,id'],
            'start_time' => ['required', 'date'],
            // Add other validation rules for the reservation time, if necessary
        ])->validate();

        try {
            $reservationId = $this->reservationService->reservePetSitter(
                $input['pet_owner_id'],
                $input['pet_sitter_id'],
                new \DateTimeImmutable($input['start_time'])
            );

            return response()->json(['reservation_id' => $reservationId], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
