<?php

namespace App\Enums\Promotion;

enum PromotionStatus: string
{
    case Active = 'active';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Активная',
            self::Expired => 'Прошедшая',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Expired => 'secondary',
        };
    }
}