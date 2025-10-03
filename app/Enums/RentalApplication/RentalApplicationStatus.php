<?php

namespace App\Enums\RentalApplication;

enum RentalApplicationStatus: string
{
    case New = 'new';
    case Active = 'active';
    case Canceled = 'canceled';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Новая',
            self::Active => 'Активная',
            self::Canceled => 'Отмененная',
            self::Completed => 'Завершенная',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::New => 'primary',
            self::Active => 'success',
            self::Canceled => 'danger',
            self::Completed => 'info',
        };
    }
}
