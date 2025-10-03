<?php

namespace App\Orchid\Layouts\PurchaseApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Models\PurchaseApplication;
use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;
use Orchid\Support\Color;

class PurchaseApplicationListTabMenu extends TabMenu
{
    protected function navigations(): iterable
    {
        return [
            Menu::make('Новые заявки')
                ->route('purchase-applications.index', ['status' => RentalApplicationStatus::New->value])
                ->badge(fn () => PurchaseApplication::where('status', RentalApplicationStatus::New)->count(), Color::PRIMARY),

            Menu::make('Активные заявки')
                ->route('purchase-applications.index', ['status' => RentalApplicationStatus::Active->value])
                ->badge(fn () => PurchaseApplication::where('status', RentalApplicationStatus::Active)->count(), Color::SUCCESS),

            Menu::make('Отмененные заявки')
                ->route('purchase-applications.index', ['status' => RentalApplicationStatus::Canceled->value])
                ->badge(fn () => PurchaseApplication::where('status', RentalApplicationStatus::Canceled)->count(), Color::WARNING),

            Menu::make('Завершенные заявки')
                ->route('purchase-applications.index', ['status' => RentalApplicationStatus::Completed->value])
                ->badge(fn () => PurchaseApplication::where('status', RentalApplicationStatus::Completed)->count(), Color::SECONDARY),
        ];
    }
}