<?php

namespace App\Orchid\Layouts\ServiceApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Models\ServiceApplication;
use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;
use Orchid\Support\Color;

class ServiceApplicationListTabMenu extends TabMenu
{
    protected function navigations(): iterable
    {
        return [
            Menu::make('Новые заявки')
                ->route('service-applications.index', ['status' => RentalApplicationStatus::New->value])
                ->badge(fn () => ServiceApplication::where('status', RentalApplicationStatus::New)->count(), Color::PRIMARY),

            Menu::make('Активные заявки')
                ->route('service-applications.index', ['status' => RentalApplicationStatus::Active->value])
                ->badge(fn () => ServiceApplication::where('status', RentalApplicationStatus::Active)->count(), Color::SUCCESS),

            Menu::make('Отмененные заявки')
                ->route('service-applications.index', ['status' => RentalApplicationStatus::Canceled->value])
                ->badge(fn () => ServiceApplication::where('status', RentalApplicationStatus::Canceled)->count(), Color::WARNING),

            Menu::make('Завершенные заявки')
                ->route('service-applications.index', ['status' => RentalApplicationStatus::Completed->value])
                ->badge(fn () => ServiceApplication::where('status', RentalApplicationStatus::Completed)->count(), Color::SECONDARY),
        ];
    }
}