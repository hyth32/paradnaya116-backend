<?php

namespace App\Orchid\Layouts\Service;

use App\Models\Service;
use Orchid\Screen\Layouts\Legend;
use Orchid\Screen\Sight;

class ServiceViewLayout extends Legend
{
    protected $target = 'service';

    protected function columns(): iterable
    {
        return [
            Sight::make('id', 'ID'),

            Sight::make('name', 'Название'),

            Sight::make('description', 'Описание'),

            Sight::make('price', 'Стоимость')
                ->render(fn (Service $service) => number_format($service->price, 2) . ' ₽'),

            Sight::make('status', 'Статус')
                ->render(fn (Service $service) => 
                    '<span class="badge bg-' . $service->status->color() . '">' . $service->status->label() . '</span>'
                ),

            Sight::make('created_at', 'Дата создания')
                ->render(fn (Service $service) => $service->created_at->format('d.m.Y H:i')),

            Sight::make('updated_at', 'Дата редактирования')
                ->render(fn (Service $service) => $service->updated_at->format('d.m.Y H:i')),
        ];
    }
}