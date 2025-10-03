<?php

namespace App\Orchid\Layouts\Service;

use App\Enums\Service\ServiceStatus;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class ServiceEditLayout extends Rows
{
    protected function fields(): iterable
    {
        return [
            Input::make('service.name')
                ->title('Название')
                ->placeholder('Введите название услуги')
                ->required(),

            Quill::make('service.description')
                ->title('Описание услуги')
                ->placeholder('Введите описание услуги')
                ->rows(10),

            Input::make('service.price')
                ->type('number')
                ->title('Стоимость услуги')
                ->placeholder('Введите стоимость услуги')
                ->help('Введите значение в формате 0.00')
                ->step('0.01')
                ->required(),

            Select::make('service.status')
                ->title('Статус')
                ->options([
                    ServiceStatus::Active->value => ServiceStatus::Active->label(),
                    ServiceStatus::Archived->value => ServiceStatus::Archived->label(),
                    ServiceStatus::Trashed->value => ServiceStatus::Trashed->label(),
                ])
                ->required(),
        ];
    }
}