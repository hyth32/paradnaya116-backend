<?php

namespace App\Orchid\Layouts\Product;

use App\Enums\Product\ProductStatus;
use App\Models\Product;
use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;
use Orchid\Support\Color;

class ProductListTabMenu extends TabMenu
{
    protected function navigations(): iterable
    {
        return [
            Menu::make('Активные товары')
                ->route('products.index', ['status' => ProductStatus::Active->value])
                ->badge(fn () => Product::query()->active()->count(), Color::SUCCESS),
            
            Menu::make('Архивированные товары')
                ->route('products.index', ['status' => ProductStatus::Archived->value])
                ->badge(fn () => Product::query()->archived()->count(), Color::SECONDARY),

            Menu::make('Удаленные товары')
                ->route('products.index', ['status' => ProductStatus::Trashed->value])
                ->badge(fn () => Product::query()->onlyTrashed()->count(), Color::DANGER),
        ];
    }
}
