<?php

namespace App\Orchid\Layouts\RentalApplication;

use App\Models\Product;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class RentalApplicatonEditLayout extends Rows
{
    protected function fields(): iterable
    {
        return [
            Input::make('rentalApplication.customer_name')
                ->title('Имя клиента')
                ->placeholder('Введите имя клиента')
                ->required(),
            
            Input::make('rentalApplication.customer_phone')
                ->title('Телефон клиента')
                ->placeholder('Введите телефон клиента')
                ->mask('+7 (999) 999-99-99')
                ->pattern('\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}'),

            Input::make('rentalApplication.customer_email')
                ->title('Email клиента')
                ->placeholder('Введите email клиента'),

            Select::make('rentalApplication.products')
                ->title('Товары')
                ->fromQuery(Product::query()->where('status', 'active'), 'name')
                ->multiple(),

            Input::make('rentalApplication.deposit')
                ->type('number')
                ->title('Сумма депозита')
                ->step('0.01')
                ->placeholder('Введите сумму депозита'),

            TextArea::make('rentalApplication.comment')
                ->title('Комментарий к заявке')
                ->placeholder('Введите комментарий к заявке')
                ->rows(10),

            DateTimer::make('rentalApplication.start_date')
                ->title('Дата начала')
                ->placeholder('Выберите дату начала')
                ->format('d.m.Y'),

            DateTimer::make('rentalApplication.end_date')
                ->title('Дата окончания')
                ->placeholder('Выберите дату окончания')
                ->format('d.m.Y'),
        ];
    }
}
