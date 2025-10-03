<?php

namespace App\Orchid\Screens\Promotion;

use App\Http\Requests\Admin\Promotion\SavePromotionRequest;
use App\Models\Promotion;
use App\Orchid\Layouts\Promotion\PromotionEditLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PromotionEditScreen extends Screen
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
        return $this->promotion->exists ? 'Редактировать акцию' : 'Создать акцию';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route('promotions.index'),

            Button::make('Сохранить')
                ->icon('bs.check')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            PromotionEditLayout::class,
        ];
    }

    public function save(Promotion $promotion, SavePromotionRequest $request)
    {
        $data = $request->validated()['promotion'];
        
        $promotion->fill($data)->save();

        Toast::success($promotion->wasRecentlyCreated ? 'Акция создана' : 'Акция обновлена');

        return redirect()->route('promotions.index');
    }
}