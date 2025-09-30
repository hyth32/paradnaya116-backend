<?php

namespace App\Enums\RentalApplication;

enum RentalApplicationType: string
{
    case Purchase = 'purchase'; // заявка на покупку
    case Service = 'service';   // заявка на услугу
    case Rental = 'rental';     // заявка на аренду
}