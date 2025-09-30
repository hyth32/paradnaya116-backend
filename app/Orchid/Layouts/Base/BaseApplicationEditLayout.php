<?php

namespace App\Orchid\Layouts\Base;

use App\Models\Product;
use App\Enums\RentalApplication\RentalApplicationType;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;

abstract class BaseApplicationEditLayout extends Rows
{
    protected function fields(): iterable
    {
        return [
            Select::make('rentalApplication.type')
                ->title('Тип заявки')
                ->options($this->getTypeOptions())
                ->required(),

            Input::make('rentalApplication.customer_name')
                ->title($this->getCustomerNameTitle())
                ->placeholder($this->getCustomerNamePlaceholder())
                ->required(),
            
            Input::make('rentalApplication.customer_phone')
                ->title($this->getCustomerPhoneTitle())
                ->placeholder($this->getCustomerPhonePlaceholder())
                ->mask('+7 (999) 999-99-99')
                ->pattern('\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}'),

            Input::make('rentalApplication.customer_email')
                ->title($this->getCustomerEmailTitle())
                ->placeholder($this->getCustomerEmailPlaceholder()),

            Select::make('rentalApplication.products')
                ->title('Товары')
                ->fromQuery(Product::query()->available(), 'name')
                ->multiple()
                ->required(),

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
                ->title($this->getStartDateTitle())
                ->placeholder($this->getStartDatePlaceholder())
                ->format('d.m.Y'),

            DateTimer::make('rentalApplication.end_date')
                ->title($this->getEndDateTitle())
                ->placeholder($this->getEndDatePlaceholder())
                ->format('d.m.Y'),
        ];
    }

    protected function getTypeOptions(): array
    {
        return [
            RentalApplicationType::Purchase->value => 'Заявка на покупку',
            RentalApplicationType::Service->value => 'Заявка на услугу',
            RentalApplicationType::Rental->value => 'Заявка на аренду',
        ];
    }

    protected function getCustomerNameTitle(): string
    {
        return 'Имя клиента';
    }

    protected function getCustomerNamePlaceholder(): string
    {
        return 'Введите имя клиента';
    }

    protected function getCustomerPhoneTitle(): string
    {
        return 'Телефон клиента';
    }

    protected function getCustomerPhonePlaceholder(): string
    {
        return 'Введите телефон клиента';
    }

    protected function getCustomerEmailTitle(): string
    {
        return 'Email клиента';
    }

    protected function getCustomerEmailPlaceholder(): string
    {
        return 'Введите email клиента';
    }

    protected function getStartDateTitle(): string
    {
        return 'Дата начала';
    }

    protected function getStartDatePlaceholder(): string
    {
        return 'Выберите дату начала';
    }

    protected function getEndDateTitle(): string
    {
        return 'Дата окончания';
    }

    protected function getEndDatePlaceholder(): string
    {
        return 'Выберите дату окончания';
    }
}