<?php

namespace App\Enums\RentalApplication;

enum RentalApplicationStatus: string
{
    case New = 'new';
    case Active = 'active';
    case Canceled = 'canceled';
    case Completed = 'completed';
}
