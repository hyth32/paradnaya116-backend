<?php

namespace App\Orchid\Layouts\Product;

use App\Models\Product;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProductListLayout extends Table
{
    protected $target = 'products';

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID'),

            TD::make('name', 'Название товара'),

            TD::make('price', 'Стоимость товара'),

            TD::make('quantity', 'Количество товара'),

            TD::make('created_at', 'Дата создания')
                ->render(fn (Product $product) => $product->created_at->format('d.m.Y H:i')),

            TD::make('updated_at', 'Дата редактирования')
                ->render(fn (Product $product) => $product->updated_at->format('d.m.Y H:i')),

            TD::make(__('Actions'))
                ->alignCenter()
                ->render(fn (Product $product) =>
                    DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Посмотреть')
                                ->icon('bs.eye')
                                ->route('products.view', $product->id),
                            
                            Link::make('Редактировать')
                                ->icon('bs.pencil')
                                ->route('products.edit', $product->id),

                            Button::make($product->isArchived() ? 'Восстановить из архива' : 'Архивировать')
                                ->canSee(!$product->trashed())
                                ->icon($product->isArchived() ? 'bs.arrow-bar-up' : 'bs.archive')
                                ->confirm($product->isArchived()
                                    ? 'Вы уверены, что хотите восстановить товар из архива?'
                                    : 'Вы уверены, что хотите переместить товар в архив?'
                                )
                                ->method('toggleArchive', [
                                    'id' => $product->id,
                                ]),

                            Button::make($product->trashed() ? 'Восстановить' : 'Удалить')
                                ->icon($product->trashed() ? 'bs.arrow-counterclockwise' : 'bs.trash')
                                ->confirm($product->trashed()
                                    ? 'Вы уверены, что хотите восстановить товар?'
                                    : 'Вы уверены, что хотите удалить товар?'
                                )
                                ->method('toggleRemove', [
                                    'id' => $product->id,
                                ]),
                        ]),
                ),
        ];
    }
}
