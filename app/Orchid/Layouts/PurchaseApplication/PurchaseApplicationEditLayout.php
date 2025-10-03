<?php

namespace App\Orchid\Layouts\PurchaseApplication;

use App\Models\Product;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;

class PurchaseApplicationEditLayout extends Rows
{
    protected function fields(): iterable
    {
        return [
            Input::make('purchaseApplication.customer_name')
                ->title('Имя клиента')
                ->placeholder('Введите имя клиента')
                ->required(),
            
            Input::make('purchaseApplication.customer_phone')
                ->title('Телефон клиента')
                ->placeholder('Введите телефон клиента')
                ->mask('+7 (999) 999-99-99')
                ->pattern('\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}'),

            Input::make('purchaseApplication.customer_email')
                ->title('Email клиента')
                ->placeholder('Введите email клиента'),

            Select::make('purchaseApplication.products')
                ->title('Товары')
                ->fromQuery(Product::query()->where('status', 'active'), 'name')
                ->multiple()
                ->required(),

            Input::make('purchaseApplication.deposit')
                ->type('number')
                ->title('Сумма депозита')
                ->step('0.01')
                ->placeholder('Введите сумму депозита'),

            TextArea::make('purchaseApplication.comment')
                ->title('Комментарий к заявке')
                ->placeholder('Введите комментарий к заявке')
                ->rows(10),
        ];
    }
}