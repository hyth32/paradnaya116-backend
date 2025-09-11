<?php

namespace App\Orchid\Layouts\Product;

use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;

class ProductListTabMenu extends TabMenu
{
    protected function navigations(): iterable
    {
        return [
            Menu::make('Активные товары')
                ->route('products.index', ['status' => 'active']),
            
            Menu::make('Архивированные товары')
                ->route('products.index', ['status' => 'archived']),

            Menu::make('Удаленные товары')
                ->route('products.index', ['status' => 'trashed']),
        ];
    }
}
