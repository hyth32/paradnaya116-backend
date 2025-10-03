<?php

namespace App\Orchid\Layouts\Promotion;

use App\Models\Promotion;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PromotionListLayout extends Table
{
    protected $target = 'promotions';

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID')
                ->sort(),

            TD::make('name', 'Название')
                ->sort()
                ->render(fn (Promotion $promotion) => 
                    Link::make($promotion->name)
                        ->route('promotions.view', $promotion)
                ),

            TD::make('image', 'Изображение')
                ->render(fn (Promotion $promotion) => 
                    $promotion->image 
                        ? '<img src="' . asset('storage/' . $promotion->image) . '" alt="' . $promotion->name . '" style="width: 50px; height: 50px; object-fit: cover;">'
                        : '<span class="text-muted">Нет изображения</span>'
                ),

            TD::make('duration', 'Период проведения')
                ->render(fn (Promotion $promotion) => $promotion->duration),

            TD::make('status', 'Статус')
                ->render(fn (Promotion $promotion) => 
                    '<span class="badge bg-' . $promotion->status->color() . '">' . $promotion->status->label() . '</span>'
                ),

            TD::make('created_at', 'Создано')
                ->render(fn (Promotion $promotion) => $promotion->created_at->format('d.m.Y H:i')),

            TD::make('actions', 'Действия')
                ->render(fn (Promotion $promotion) => 
                    DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Просмотр')
                                ->icon('bs.eye')
                                ->route('promotions.view', $promotion),

                            Link::make('Редактировать')
                                ->icon('bs.pencil')
                                ->route('promotions.edit', $promotion),

                            Button::make($promotion->isActive() ? 'Завершить' : 'Активировать')
                                ->icon($promotion->isActive() ? 'bs.stop-circle' : 'bs.play-circle')
                                ->method('toggleArchive', ['promotion' => $promotion->id])
                                ->canSee(!$promotion->isTrashed()),

                            Button::make($promotion->isTrashed() ? 'Восстановить' : 'Удалить')
                                ->icon($promotion->isTrashed() ? 'bs.arrow-clockwise' : 'bs.trash')
                                ->method('toggleRemove', ['promotion' => $promotion->id])
                                ->confirm('Вы уверены, что хотите удалить эту акцию?'),
                        ])
                ),
        ];
    }
}