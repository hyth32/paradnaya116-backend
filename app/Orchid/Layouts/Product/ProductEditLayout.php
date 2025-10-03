<?php

namespace App\Orchid\Layouts\Product;

use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Upload;

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
                    'active' => 'Активный',
                    'archived' => 'Архивный',
                    'trashed' => 'Удаленный',
                ])
                ->required(),

            Picture::make('product.main_image')
                ->title('Главное изображение')
                ->placeholder('Выберите главное изображение товара')
                ->acceptedFiles('image/*')
                ->storage('public')
                ->path('products/main'),

            Upload::make('product.detail_images')
                ->title('Изображения деталей')
                ->placeholder('Выберите изображения деталей товара')
                ->acceptedFiles('image/*')
                ->storage('public')
                ->path('products/detail')
                ->maxFiles(10),
        ];
    }
}
