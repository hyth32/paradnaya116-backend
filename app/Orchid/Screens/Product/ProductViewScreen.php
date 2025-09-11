<?php

namespace App\Orchid\Screens\Product;

use App\Models\Product;
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
            Link::make('Редактировать')
                ->icon('bs.pencil')
                ->route('products.edit', $this->product->id),

            Button::make($this->product->is_archived ? 'Восстановить из архива' : 'Архивировать')
                ->canSee(!$this->product->is_deleted)
                ->icon($this->product->is_archived ? 'bs.arrow-bar-up' : 'bs.archive')
                ->confirm($this->product->is_archived
                    ? 'Вы уверены, что хотите восстановить товар из архива?'
                    : 'Вы уверены, что хотите переместить товар в архив?'
                )
                ->method('toggleArchive', [
                    'id' => $this->product->id,
                ]),

            Button::make($this->product->is_deleted ? 'Восстановить' : 'Удалить')
                ->icon($this->product->is_deleted ? 'bs.arrow-counterclockwise' : 'bs.trash')
                ->confirm($this->product->is_deleted
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

        if ($product->is_archived) {
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

        if ($product->is_deleted) {
            $product->restore();
            Toast::success('Товар восстановлен');
        } else {
            $product->delete();
            Toast::success('Товар удален');
        }
    }

    public function layout(): iterable
    {
        return [
            ProductViewLayout::class,
        ];
    }
}
