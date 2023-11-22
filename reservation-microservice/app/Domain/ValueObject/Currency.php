<?php

namespace App\Domain\ValueObject;

enum Currency: string {
    case USD = 'American USD';
    case UAH = 'Ukrainian UAH';
    case EUR = 'European EUR';
}
