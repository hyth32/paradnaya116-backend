<?php

namespace App\Orchid\Layouts\User;

use App\Orchid\Filters\RoleFilter;
use Orchid\Screen\Layouts\Selection;

class UserFiltersLayout extends Selection
{
    public function filters(): array
    {
        return [
            RoleFilter::class,
        ];
    }
}
