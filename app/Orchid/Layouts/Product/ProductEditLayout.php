<?php

namespace App\Orchid\Layouts\Product;

use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;

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
        ];
    }
}
