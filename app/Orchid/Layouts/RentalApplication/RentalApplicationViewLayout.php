<?php

namespace App\Orchid\Layouts\RentalApplication;

use App\Models\RentalApplication;
use Orchid\Screen\Layouts\Legend;
use Orchid\Screen\Sight;

class RentalApplicationViewLayout extends Legend
{
    protected $target = 'rentalApplication';

    protected function columns(): iterable
    {
        return [
            Sight::make('id', 'ID'),

            Sight::make('customer', 'Данные арендатора')
                ->render(fn (RentalApplication $rentalApplication) => 
                    view('orchid.rental-application.customer-info', [
                        'rentalApplication' => $rentalApplication,
                    ]),
                ),

            Sight::make('deposit', 'Депозит'),

            Sight::make('comment', 'Комментарий к заявке'),

            Sight::make('total_price', 'Итоговая стоимость'),

            Sight::make('start_date', 'Дата начала аренды')
                ->render(fn (RentalApplication $rentalApplication) => $rentalApplication->created_at->format('d.m.Y')),

            Sight::make('end_date', 'Дата окончания аренды')
                ->render(fn (RentalApplication $rentalApplication) => $rentalApplication->created_at->format('d.m.Y')),

            Sight::make('created_at', 'Дата создания')
                ->render(fn (RentalApplication $rentalApplication) => $rentalApplication->created_at->format('d.m.Y H:i')),

            Sight::make('updated_at', 'Дата редактирования')
                ->render(fn (RentalApplication $rentalApplication) => $rentalApplication->updated_at->format('d.m.Y H:i')),
        ];
    }
}
