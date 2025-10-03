<?php

namespace App\Orchid\Layouts\RentalApplication;

use App\Orchid\Layouts\Base\BaseApplicationListLayout;

class RentalApplicationListLayout extends BaseApplicationListLayout
{
    protected function getViewRouteName(): string
    {
        return 'rental-applications.view';
    }

    protected function getEditRouteName(): string
    {
        return 'rental-applications.edit';
    }
}
