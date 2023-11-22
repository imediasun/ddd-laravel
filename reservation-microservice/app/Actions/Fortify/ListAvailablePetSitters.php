<?php

namespace App\Actions\Fortify;

use AllowDynamicProperties;
use App\Application\ReservationService;
use Illuminate\Http\Request;
#[AllowDynamicProperties] class ListAvailablePetSitters
{

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function getAvailablePetSitters(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $availableSitters = $this->reservationService->listAvailablePetSitters();

            return response()->json(['available_pet_sitters' => $availableSitters], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
