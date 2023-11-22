<?php

namespace App\Domain\ValueObject\Reservation;

enum Status: string {
    case NEW = 'New';
    case PROCESSING = 'Processing';
    case PAID = 'Paid';
    case REJECTED = 'Rejected';
}
