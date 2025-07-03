<?php

namespace App\Support\Enums\Currency;

enum CurrencyFormat: string
{
    case RUB = "x ₽";
    case USD = "\$x";
    case EUR = "€x";
}
