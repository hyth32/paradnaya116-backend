<?php

namespace App\Orchid\Screens\Promotion;

use App\Models\Promotion;
use App\Orchid\Layouts\Promotion\PromotionViewLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PromotionViewScreen extends Screen
{
    public ?Promotion $promotion = null;

    public function query(Promotion $promotion): iterable
    {
        return [
            'promotion' => $promotion,
        ];
    }

    public function name(): ?string
    {
        return $this->promotion->name;
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route('promotions.index'),

            Link::make('Редактировать')
                ->icon('bs.pencil')
                ->route('promotions.edit', $this->promotion),

            Button::make($this->promotion->isActive() ? 'Завершить' : 'Активировать')
                ->icon($this->promotion->isActive() ? 'bs.stop-circle' : 'bs.play-circle')
                ->method('toggleStatus')
                ->canSee(!$this->promotion->isTrashed()),

            Button::make($this->promotion->isTrashed() ? 'Восстановить' : 'Удалить')
                ->icon($this->promotion->isTrashed() ? 'bs.arrow-clockwise' : 'bs.trash')
                ->method('toggleRemove')
                ->confirm('Вы уверены, что хотите удалить эту акцию?'),
        ];
    }

    public function layout(): iterable
    {
        return [
            PromotionViewLayout::class,
        ];
    }

    public function toggleStatus()
    {
        if ($this->promotion->isActive()) {
            $this->promotion->update(['status' => \App\Enums\Promotion\PromotionStatus::Expired]);
            Toast::success('Акция завершена');
        } else {
            $this->promotion->update(['status' => \App\Enums\Promotion\PromotionStatus::Active]);
            Toast::success('Акция активирована');
        }
    }

    public function toggleRemove()
    {
        if ($this->promotion->isTrashed()) {
            $this->promotion->restore();
            Toast::success('Акция восстановлена');
        } else {
            $this->promotion->delete();
            Toast::success('Акция удалена');
        }
    }
}