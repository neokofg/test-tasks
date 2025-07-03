<?php

namespace App\Support\Enums\Currency;

enum Currency: int
{
    case RUB = 1;

    case USD = 90;

    case EUR = 100;

    public static function getNames(): array
    {
        return array_column(self::cases(), 'name');
    }

    public function getFormat(): CurrencyFormat
    {
        return match($this) {
            self::RUB => CurrencyFormat::RUB,
            self::USD => CurrencyFormat::USD,
            self::EUR => CurrencyFormat::EUR,
        };
    }

    public function formatPrice(float $price): string
    {
        return str_replace('x', number_format($price, 2), $this->getFormat()->value);
    }
}
