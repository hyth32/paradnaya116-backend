<?php

namespace App\Orchid\Layouts\ServiceApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Enums\RentalApplication\RentalApplicationType;
use App\Models\RentalApplication;
use App\Orchid\Layouts\Base\BaseApplicationListTabLayout;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class ServiceApplicationListTabLayout extends BaseApplicationListTabLayout
{
    protected string $routeName = 'service-applications.index';

    protected function navigations(): iterable
    {
        return [
            Menu::make('Новые заявки')
                ->route($this->routeName, ['status' => RentalApplicationStatus::New->value])
                ->badge(fn () => RentalApplication::query()->new()->where('type', RentalApplicationType::Service)->count(), Color::PRIMARY),

            Menu::make('Активные заявки')
                ->route($this->routeName, ['status' => RentalApplicationStatus::Active->value])
                ->badge(fn () => RentalApplication::query()->active()->where('type', RentalApplicationType::Service)->count(), Color::SUCCESS),

            Menu::make('Отмененные заявки')
                ->route($this->routeName, ['status' => RentalApplicationStatus::Canceled->value])
                ->badge(fn () => RentalApplication::query()->canceled()->where('type', RentalApplicationType::Service)->count(), Color::SECONDARY),

            Menu::make('Выполненные заявки')
                ->route($this->routeName, ['status' => RentalApplicationStatus::Completed->value])
                ->badge(fn () => RentalApplication::query()->completed()->where('type', RentalApplicationType::Service)->count(), Color::INFO),
        ];
    }
}