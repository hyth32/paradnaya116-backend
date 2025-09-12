<?php

namespace App\Orchid\Layouts\RentalApplication;

use App\Models\Product;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;

class RentalApplicatonEditLayout extends Rows
{
    protected function fields(): iterable
    {
        return [
            Input::make('rentalApplication.customer_name')
                ->title('Имя арендатора')
                ->placeholder('Введите имя арендатора')
                ->required(),
            
            Input::make('rentalApplication.customer_phone')
                ->title('Телефон арендатора')
                ->placeholder('Введите телефон арендатора')
                ->mask('+7 (999) 999-99-99')
                ->pattern('\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}'),

            Input::make('rentalApplication.customer_email')
                ->title('Email арендатора')
                ->placeholder('Введите email арендатора'),

            Select::make('rentalApplication.products')
                ->title('Товары')
                ->fromQuery(Product::query()->available(), 'name')
                ->multiple()
                ->required(),

            Input::make('rentalApplication.deposit')
                ->type('number')
                ->title('Сумма депозита')
                ->step('0.01')
                ->placeholder('Введите cумму депозита'),

            TextArea::make('rentalApplication.comment')
                ->title('Комментарий к заявке')
                ->placeholder('Введите комментарий к заявке')
                ->rows(10),

            DateTimer::make('rentalApplication.start_date')
                ->title('Дата начала аренды')
                ->placeholder('Выберите дату начала аренды')
                ->format('d.m.Y'),

            DateTimer::make('rentalApplication.end_date')
                ->title('Дата окончания аренды')
                ->placeholder('Выберите дату окончания аренды')
                ->format('d.m.Y'),
        ];
    }
}
