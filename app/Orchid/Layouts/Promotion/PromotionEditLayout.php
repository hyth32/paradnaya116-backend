<?php

namespace App\Orchid\Layouts\Promotion;

use App\Enums\Promotion\PromotionStatus;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;

class PromotionEditLayout extends Rows
{
    protected function fields(): iterable
    {
        return [
            Input::make('promotion.name')
                ->title('Название акции')
                ->placeholder('Введите название акции')
                ->required(),

            Picture::make('promotion.image')
                ->title('Изображение')
                ->placeholder('Выберите изображение для акции')
                ->acceptedFiles('image/*')
                ->storage('public')
                ->path('promotions'),

            TextArea::make('promotion.description')
                ->title('Описание')
                ->placeholder('Введите описание акции')
                ->rows(5),

            DateTimer::make('promotion.start_date')
                ->title('Дата начала')
                ->placeholder('Выберите дату начала акции')
                ->format('d.m.Y H:i')
                ->required(),

            DateTimer::make('promotion.end_date')
                ->title('Дата окончания')
                ->placeholder('Выберите дату окончания акции')
                ->format('d.m.Y H:i')
                ->required(),

            Select::make('promotion.status')
                ->title('Статус')
                ->options([
                    'active' => 'Активная',
                    'expired' => 'Завершенная',
                ])
                ->required(),
        ];
    }
}