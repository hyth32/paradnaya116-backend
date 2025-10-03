<?php

namespace App\Orchid\Layouts\Product;

use App\Enums\Product\ProductStatus;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;

class ProductEditLayout extends Rows
{
    protected function fields(): iterable
    {
        return [
            Input::make('product.name')
                ->title('Название')
                ->placeholder('Введите название товара')
                ->required(),

            Quill::make('product.description')
                ->title('Описание товара')
                ->placeholder('Введите описание товара')
                ->rows(10),

            Input::make('product.price')
                ->type('number')
                ->title('Стоимость товара')
                ->placeholder('Введите стоимость товара')
                ->help('Введите значение в формате 0.00')
                ->step('0.01')
                ->required(),
            
            Input::make('product.quantity')
                ->type('number')
                ->title('Количество товара')
                ->placeholder('Введите количество товара')
                ->required(),

            Select::make('product.status')
                ->title('Статус')
                ->options([
                    ProductStatus::Active->value => ProductStatus::Active->label(),
                    ProductStatus::Archived->value => ProductStatus::Archived->label(),
                    ProductStatus::Trashed->value => ProductStatus::Trashed->label(),
                ])
                ->required(),
        ];
    }
}
