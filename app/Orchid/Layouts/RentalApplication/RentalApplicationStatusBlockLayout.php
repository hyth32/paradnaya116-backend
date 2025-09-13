<?php

namespace App\Orchid\Layouts\RentalApplication;

use Orchid\Screen\Layout;
use Orchid\Screen\Repository;

class RentalApplicationStatusBlockLayout extends Layout
{
    protected $template = 'orchid.rental-application.status';

    public function build(Repository $repository)
    {
        return view($this->template, [
            'rentalApplication' => $repository->get('rentalApplication'),
        ]);
    }
}
