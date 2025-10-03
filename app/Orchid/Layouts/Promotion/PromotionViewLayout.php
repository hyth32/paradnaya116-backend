<?php

namespace App\Orchid\Layouts\Promotion;

use Orchid\Screen\Layouts\Legend;
use Orchid\Screen\Sight;

class PromotionViewLayout extends Legend
{
    protected $target = 'promotion';

    protected function columns(): iterable
    {
        return [
            Sight::make('name', 'Название'),

            Sight::make('image', 'Изображение')
                ->render(fn ($promotion) => 
                    $promotion->image 
                        ? '<img src="' . asset('storage/' . $promotion->image) . '" alt="' . $promotion->name . '" style="max-width: 300px; max-height: 200px; object-fit: cover;">'
                        : '<span class="text-muted">Изображение не загружено</span>'
                ),

            Sight::make('description', 'Описание')
                ->render(fn ($promotion) => 
                    $promotion->description ?: '<span class="text-muted">Описание не указано</span>'
                ),

            Sight::make('duration', 'Период проведения'),

            Sight::make('start_date', 'Дата начала')
                ->render(fn ($promotion) => $promotion->start_date->format('d.m.Y H:i')),

            Sight::make('end_date', 'Дата окончания')
                ->render(fn ($promotion) => $promotion->end_date->format('d.m.Y H:i')),

            Sight::make('status', 'Статус')
                ->render(fn ($promotion) => 
                    '<span class="badge bg-' . $promotion->status->color() . '">' . $promotion->status->label() . '</span>'
                ),

            Sight::make('created_at', 'Создано')
                ->render(fn ($promotion) => $promotion->created_at->format('d.m.Y H:i')),

            Sight::make('updated_at', 'Обновлено')
                ->render(fn ($promotion) => $promotion->updated_at->format('d.m.Y H:i')),
        ];
    }
}