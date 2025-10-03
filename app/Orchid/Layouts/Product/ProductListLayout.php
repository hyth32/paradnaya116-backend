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

            TD::make('price', 'Стоимость товара')
                ->render(fn (Product $product) => number_format($product->price, 2) . ' ₽'),

            TD::make('quantity', 'Количество товара'),

            TD::make('available_for_rental', 'Доступно для аренды')
                ->render(fn (Product $product) => $product->getAvailableForRental()),

            TD::make('available_for_purchase', 'Доступно для покупки')
                ->render(fn (Product $product) => $product->getAvailableForPurchase()),

            TD::make('reserved_rental', 'Зарезервировано для аренды')
                ->render(fn (Product $product) => $product->getRentalReservedQuantity()),

            TD::make('reserved_purchase', 'Зарезервировано для покупки')
                ->render(fn (Product $product) => $product->getPurchaseReservedQuantity()),

            TD::make('status', 'Статус')
                ->render(fn (Product $product) => 
                    '<span class="badge bg-' . $product->status->color() . '">' . $product->status->label() . '</span>'
                ),

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
                                ->canSee(!$product->isTrashed())
                                ->icon($product->isArchived() ? 'bs.arrow-bar-up' : 'bs.archive')
                                ->confirm($product->isArchived()
                                    ? 'Вы уверены, что хотите восстановить товар из архива?'
                                    : 'Вы уверены, что хотите переместить товар в архив?'
                                )
                                ->method('toggleArchive', [
                                    'id' => $product->id,
                                ]),

                            Button::make($product->isTrashed() ? 'Восстановить' : 'Удалить')
                                ->icon($product->isTrashed() ? 'bs.arrow-counterclockwise' : 'bs.trash')
                                ->confirm($product->isTrashed()
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
