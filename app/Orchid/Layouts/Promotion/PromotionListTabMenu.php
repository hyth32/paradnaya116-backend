<?php

namespace App\Orchid\Layouts\Promotion;

use App\Models\Promotion;
use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;
use Orchid\Support\Color;

class PromotionListTabMenu extends TabMenu
{
    public function navigations(): array
    {
        return [
            Menu::make('Активные')
                ->route('promotions.index', ['status' => 'active'])
                ->badge(fn () => Promotion::active()->count(), Color::SUCCESS),

            Menu::make('Прошедшие')
                ->route('promotions.index', ['status' => 'expired'])
                ->badge(fn () => Promotion::expired()->count(), Color::SECONDARY),
        ];
    }
}