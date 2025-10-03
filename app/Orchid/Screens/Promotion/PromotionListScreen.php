<?php

namespace App\Orchid\Screens\Promotion;

use App\Models\Promotion;
use App\Orchid\Layouts\Promotion\PromotionListLayout;
use App\Orchid\Layouts\Promotion\PromotionListTabMenu;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PromotionListScreen extends Screen
{
    public function query(): iterable
    {
        $status = request()->get('status', 'active');
        $query = Promotion::defaultSort('id', 'desc');
        
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        return [
            'promotions' => $query->paginate(),
            'status' => $status,
        ];
    }

    public function name(): ?string
    {
        return 'Акции';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Добавить акцию')
                ->icon('bs.plus-circle')
                ->route('promotions.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            PromotionListTabMenu::class,
            PromotionListLayout::class,
        ];
    }

    public function toggleArchive(Promotion $promotion)
    {
        if ($promotion->isActive()) {
            $promotion->update(['status' => \App\Enums\Promotion\PromotionStatus::Expired]);
            Toast::success('Акция завершена');
        } else {
            $promotion->update(['status' => \App\Enums\Promotion\PromotionStatus::Active]);
            Toast::success('Акция активирована');
        }
        
        return redirect()->route('promotions.index', ['status' => request()->get('status', 'active')]);
    }

    public function toggleRemove(Promotion $promotion)
    {
        if ($promotion->isTrashed()) {
            $promotion->restore();
            Toast::success('Акция восстановлена');
        } else {
            $promotion->delete();
            Toast::success('Акция удалена');
        }
        
        return redirect()->route('promotions.index', ['status' => request()->get('status', 'active')]);
    }
}