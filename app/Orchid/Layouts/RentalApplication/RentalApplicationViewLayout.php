<?php

namespace App\Orchid\Layouts\RentalApplication;

use App\Models\RentalApplication;
use Illuminate\Contracts\View\View;
use Orchid\Screen\Layouts\Legend;
use Orchid\Screen\Sight;

class RentalApplicationViewLayout extends Legend
{
    protected $target = 'rentalApplication';

    protected function columns(): iterable
    {
        return [
            Sight::make('id', 'ID'),

            Sight::make('customer', 'Данные клиента')
                ->render(fn (RentalApplication $application) => 
                    $this->getCustomerInfoView($application)
                ),

            Sight::make('deposit', 'Депозит'),

            Sight::make('comment', 'Комментарий к заявке'),

            Sight::make('total_price', 'Итоговая стоимость'),

            Sight::make('start_date', 'Дата начала')
                ->render(fn (RentalApplication $application) => $application->start_date->format('d.m.Y')),

            Sight::make('end_date', 'Дата окончания')
                ->render(fn (RentalApplication $application) => $application->end_date->format('d.m.Y')),

            Sight::make('created_at', 'Дата создания')
                ->render(fn (RentalApplication $application) => $application->created_at->format('d.m.Y H:i')),

            Sight::make('updated_at', 'Дата редактирования')
                ->render(fn (RentalApplication $application) => $application->updated_at->format('d.m.Y H:i')),
        ];
    }

    protected function getCustomerInfoView($application): View
    {
        return view('orchid.rental-application.customer-info', [
            'rentalApplication' => $application,
        ]);
    }
}
