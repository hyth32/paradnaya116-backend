<?php

namespace App\Orchid\Layouts\ServiceApplication;

use App\Models\Service;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;

class ServiceApplicationEditLayout extends Rows
{
    protected function fields(): iterable
    {
        return [
            Input::make('serviceApplication.customer_name')
                ->title('Имя клиента')
                ->placeholder('Введите имя клиента')
                ->required(),
            
            Input::make('serviceApplication.customer_phone')
                ->title('Телефон клиента')
                ->placeholder('Введите телефон клиента')
                ->mask('+7 (999) 999-99-99')
                ->pattern('\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}'),

            Input::make('serviceApplication.customer_email')
                ->title('Email клиента')
                ->placeholder('Введите email клиента'),

            Select::make('serviceApplication.services')
                ->title('Услуги')
                ->fromQuery(Service::query()->active(), 'name')
                ->multiple()
                ->required(),

            Input::make('serviceApplication.deposit')
                ->type('number')
                ->title('Сумма депозита')
                ->step('0.01')
                ->placeholder('Введите сумму депозита'),

            TextArea::make('serviceApplication.comment')
                ->title('Комментарий к заявке')
                ->placeholder('Введите комментарий к заявке')
                ->rows(10),

            DateTimer::make('serviceApplication.start_date')
                ->title('Дата начала услуги')
                ->placeholder('Выберите дату начала услуги')
                ->format('d.m.Y'),

            DateTimer::make('serviceApplication.end_date')
                ->title('Дата окончания услуги')
                ->placeholder('Выберите дату окончания услуги')
                ->format('d.m.Y'),
        ];
    }
}