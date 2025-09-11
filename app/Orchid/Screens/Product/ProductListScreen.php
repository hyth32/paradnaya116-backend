<?php

namespace App\Orchid\Screens\Product;

use App\Enums\Product\ProductStatus;
use App\Models\Product;
use App\Orchid\Layouts\Product\ProductListLayout;
use App\Orchid\Layouts\Product\ProductListTabMenu;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ProductListScreen extends Screen
{
    public function query(): iterable
    {
        $status = request()->get('status', ProductStatus::Active->value);

        $query = Product::with('images')->defaultSort('id', 'desc');

        match ($status) {
            ProductStatus::Archived->value => $query->archived(),
            ProductStatus::Trashed->value => $query->onlyTrashed(),
            default => $query->active(),
        };

        return [
            'products' => $query->paginate(),
            'status' => $status,
        ];
    }

    public function name(): ?string
    {
        return 'Каталог товаров';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->route('products.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            ProductListTabMenu::class,
            ProductListLayout::class,
        ];
    }

    public function toggleArchive(int $id): void
    {
        $product = Product::findOrFail($id);

        if ($product->isArchived()) {
            $product->unarchive();
            Toast::success('Товар восстановлен из архива');
        } else {
            $product->archive();
            Toast::success('Товар перенесен в архив');
        }
    }

    public function toggleRemove(int $id): void
    {
        $product = Product::withTrashed()->findOrFail($id);

        $product->unarchive();

        if ($product->trashed()) {
            $product->restore();
            Toast::success('Товар восстановлен');
        } else {
            $product->delete();
            Toast::success('Товар удален');
        }
    }
}
