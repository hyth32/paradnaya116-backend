<?php

namespace App\Orchid\Layouts\Product;

use App\Models\Product;
use Orchid\Screen\Layouts\Legend;
use Orchid\Screen\Sight;

class ProductViewLayout extends Legend
{
    protected $target = 'product';

    protected function columns(): iterable
    {
        return [
            Sight::make('id', 'ID'),

            Sight::make('name', 'Название'),

            Sight::make('description', 'Описание'),

            Sight::make('price', 'Стоимость')
                ->render(fn (Product $product) => number_format($product->price, 2) . ' ₽'),

            Sight::make('quantity', 'Общее количество'),

            Sight::make('available_for_rental', 'Доступно для аренды')
                ->render(fn (Product $product) => $product->getAvailableForRental()),

            Sight::make('available_for_purchase', 'Доступно для покупки')
                ->render(fn (Product $product) => $product->getAvailableForPurchase()),

            Sight::make('reserved_rental', 'Зарезервировано для аренды')
                ->render(fn (Product $product) => $product->getRentalReservedQuantity()),

            Sight::make('reserved_purchase', 'Зарезервировано для покупки')
                ->render(fn (Product $product) => $product->getPurchaseReservedQuantity()),

            Sight::make('main_image', 'Главное изображение')
                ->render(function (Product $product) {
                    $mainImagePath = $product->getMainImagePath();
                    if ($mainImagePath) {
                        return '<img src="' . asset('storage/' . $mainImagePath) . '" alt="Главное изображение" style="max-width: 200px; max-height: 200px; object-fit: cover;">';
                    }
                    return '<span class="text-muted">Изображение не загружено</span>';
                }),

            Sight::make('detail_images', 'Изображения деталей')
                ->render(function (Product $product) {
                    $detailImages = $product->detailImages;
                    if ($detailImages->count() > 0) {
                        $html = '<div style="display: flex; flex-wrap: wrap; gap: 10px;">';
                        foreach ($detailImages as $image) {
                            $html .= '<img src="' . asset('storage/' . $image->path) . '" alt="Изображение детали" style="max-width: 150px; max-height: 150px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">';
                        }
                        $html .= '</div>';
                        return $html;
                    }
                    return '<span class="text-muted">Изображения деталей не загружены</span>';
                }),

            Sight::make('status', 'Статус')
                ->render(fn (Product $product) => 
                    '<span class="badge bg-' . $product->status->color() . '">' . $product->status->label() . '</span>'
                ),

            Sight::make('created_at', 'Дата создания')
                ->render(fn (Product $product) => $product->created_at->format('d.m.Y H:i')),

            Sight::make('updated_at', 'Дата редактирования')
                ->render(fn (Product $product) => $product->updated_at->format('d.m.Y H:i')),
        ];
    }
}
