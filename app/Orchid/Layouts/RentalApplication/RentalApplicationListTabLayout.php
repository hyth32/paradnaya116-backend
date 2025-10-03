<?php

namespace App\Orchid\Layouts\RentalApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Models\RentalApplication;
use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;
use Orchid\Support\Color;

class RentalApplicationListTabLayout extends TabMenu
{
    protected function navigations(): iterable
    {
        return [
            Menu::make('Новые заявки')
                ->route('rental-applications.index', ['status' => RentalApplicationStatus::New->value])
                ->badge(fn () => RentalApplication::query()->new()->count(), Color::PRIMARY),

            Menu::make('Активные заявки')
                ->route('rental-applications.index', ['status' => RentalApplicationStatus::Active->value])
                ->badge(fn () => RentalApplication::query()->active()->count(), Color::SUCCESS),

            Menu::make('Отмененные заявки')
                ->route('rental-applications.index', ['status' => RentalApplicationStatus::Canceled->value])
                ->badge(fn () => RentalApplication::query()->canceled()->count(), Color::SECONDARY),

            Menu::make('Выполненные заявки')
                ->route('rental-applications.index', ['status' => RentalApplicationStatus::Completed->value])
                ->badge(fn () => RentalApplication::query()->completed()->count(), Color::INFO),
        ];
    }
}
