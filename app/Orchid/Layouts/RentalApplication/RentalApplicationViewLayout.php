<?php

namespace App\Orchid\Layouts\RentalApplication;

use App\Orchid\Layouts\Base\BaseApplicationViewLayout;
use Illuminate\Contracts\View\View;

class RentalApplicationViewLayout extends BaseApplicationViewLayout
{
    protected function getCustomerInfoView($application): View
    {
        return view('orchid.rental-application.customer-info', [
            'rentalApplication' => $application,
        ]);
    }
}
