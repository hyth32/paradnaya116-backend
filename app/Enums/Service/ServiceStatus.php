<?php

namespace App\Enums\Service;

enum ServiceStatus: string
{
    case Active = 'active';
    case Archived = 'archived';
    case Trashed = 'trashed';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Активная',
            self::Archived => 'Архивная',
            self::Trashed => 'Удаленная',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Archived => 'warning',
            self::Trashed => 'danger',
        };
    }
}