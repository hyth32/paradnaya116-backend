<?php

namespace App\Enums\RentalApplication;

enum RentalApplicationType: string
{
    case Purchase = 'purchase'; // заявка на покупку
    case Service = 'service';   // заявка на услугу
    case Rental = 'rental';     // заявка на аренду

    public function label(): string
    {
        return match ($this) {
            self::Purchase => 'Покупка',
            self::Service => 'Услуга',
            self::Rental => 'Аренда',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Purchase => 'warning',
            self::Service => 'info',
            self::Rental => 'primary',
        };
    }
}
