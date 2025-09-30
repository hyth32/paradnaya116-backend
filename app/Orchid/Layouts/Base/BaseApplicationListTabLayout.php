<?php

namespace App\Orchid\Layouts\Base;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Models\RentalApplication;
use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;
use Orchid\Support\Color;

class BaseApplicationListTabLayout extends TabMenu
{
    protected string $routeName = 'rental-applications.index';
    protected string $modelClass = RentalApplication::class;

    protected function navigations(): iterable
    {
        return [
            Menu::make('Новые заявки')
                ->route($this->routeName, ['status' => RentalApplicationStatus::New->value])
                ->badge(fn () => $this->modelClass::query()->new()->count(), Color::PRIMARY),

            Menu::make('Активные заявки')
                ->route($this->routeName, ['status' => RentalApplicationStatus::Active->value])
                ->badge(fn () => $this->modelClass::query()->active()->count(), Color::SUCCESS),

            Menu::make('Отмененные заявки')
                ->route($this->routeName, ['status' => RentalApplicationStatus::Canceled->value])
                ->badge(fn () => $this->modelClass::query()->canceled()->count(), Color::SECONDARY),

            Menu::make('Выполненные заявки')
                ->route($this->routeName, ['status' => RentalApplicationStatus::Completed->value])
                ->badge(fn () => $this->modelClass::query()->completed()->count(), Color::INFO),
        ];
    }
}