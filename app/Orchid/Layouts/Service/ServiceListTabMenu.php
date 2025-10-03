<?php

namespace App\Orchid\Layouts\Service;

use App\Enums\Service\ServiceStatus;
use App\Models\Service;
use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;
use Orchid\Support\Color;

class ServiceListTabMenu extends TabMenu
{
    protected function navigations(): iterable
    {
        return [
            Menu::make('Активные услуги')
                ->route('services.index', ['status' => ServiceStatus::Active->value])
                ->badge(fn () => Service::query()->active()->count(), Color::SUCCESS),
            
            Menu::make('Архивированные услуги')
                ->route('services.index', ['status' => ServiceStatus::Archived->value])
                ->badge(fn () => Service::query()->archived()->count(), Color::SECONDARY),

            Menu::make('Удаленные услуги')
                ->route('services.index', ['status' => ServiceStatus::Trashed->value])
                ->badge(fn () => Service::query()->trashed()->count(), Color::DANGER),
        ];
    }
}