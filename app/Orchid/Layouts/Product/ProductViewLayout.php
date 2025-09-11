<?php

namespace App\Orchid\Layouts\Product;

use App\Models\Product;
use Orchid\Screen\Layouts\Legend;
use Orchid\Screen\Sight;

class ProductViewLayout extends Legend
{
    protected $target = 'product';

    protected function columns(): iterable
    {
        return [
            Sight::make('id', 'ID'),

            Sight::make('name', 'Название'),

            Sight::make('description', 'Описание'),

            Sight::make('price', 'Стоимость'),

            Sight::make('quantity', 'Количество'),

            Sight::make('created_at', 'Дата создания')
                ->render(fn (Product $product) => $product->created_at->format('d.m.Y H:i')),

            Sight::make('updated_at', 'Дата редактирования')
                ->render(fn (Product $product) => $product->updated_at->format('d.m.Y H:i')),
        ];
    }
}
