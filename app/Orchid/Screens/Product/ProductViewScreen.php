<?php

namespace App\Orchid\Screens\Product;

use App\Enums\Product\ProductStatus;
use App\Models\Product;
use App\Orchid\Layouts\Product\ProductStatusBlockLayout;
use App\Orchid\Layouts\Product\ProductViewLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ProductViewScreen extends Screen
{
    public ?Product $product = null;

    public function query(Product $product): iterable
    {
        $product->load(['images', 'mainImage', 'detailImages']);
        
        return [
            'product' => $product,
        ];
    }

    public function name(): ?string
    {
        return $this->product->name;
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route('products.index'),

            Link::make('Редактировать')
                ->icon('bs.pencil')
                ->route('products.edit', $this->product->id),

            Button::make($this->product->isArchived() ? 'Восстановить из архива' : 'Архивировать')
                ->canSee(!$this->product->isTrashed())
                ->icon($this->product->isArchived() ? 'bs.arrow-bar-up' : 'bs.archive')
                ->confirm($this->product->isArchived()
                    ? 'Вы уверены, что хотите восстановить товар из архива?'
                    : 'Вы уверены, что хотите переместить товар в архив?'
                )
                ->method('toggleArchive', [
                    'id' => $this->product->id,
                ]),

            Button::make($this->product->isTrashed() ? 'Восстановить' : 'Удалить')
                ->icon($this->product->isTrashed() ? 'bs.arrow-counterclockwise' : 'bs.trash')
                ->confirm($this->product->isTrashed()
                    ? 'Вы уверены, что хотите восстановить товар?'
                    : 'Вы уверены, что хотите удалить товар?'
                )
                ->method('toggleRemove', [
                    'id' => $this->product->id,
                ]),
        ];
    }

    public function toggleArchive(int $id): void
    {
        $product = Product::findOrFail($id);

        if ($product->isArchived()) {
            $product->status = ProductStatus::Active;
            $product->save();
            Toast::success('Товар восстановлен из архива');
        } else {
            $product->status = ProductStatus::Archived;
            $product->save();
            Toast::success('Товар перенесен в архив');
        }
    }

    public function toggleRemove(int $id): void
    {
        $product = Product::findOrFail($id);

        if ($product->isTrashed()) {
            $product->status = ProductStatus::Active;
            $product->save();
            Toast::success('Товар восстановлен');
        } else {
            $product->status = ProductStatus::Trashed;
            $product->save();
            Toast::success('Товар удален');
        }
    }

    public function layout(): iterable
    {
        return [
            ProductStatusBlockLayout::class,
            ProductViewLayout::class,
        ];
    }
}
