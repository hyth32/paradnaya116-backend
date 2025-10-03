<?php

namespace App\Orchid\Layouts\PurchaseApplication;

use App\Models\PurchaseApplication;
use Orchid\Screen\Layouts\Legend;
use Orchid\Screen\Sight;

class PurchaseApplicationViewLayout extends Legend
{
    protected $target = 'purchaseApplication';

    protected function columns(): iterable
    {
        return [
            Sight::make('id', 'ID'),

            Sight::make('customer_name', 'Имя клиента'),

            Sight::make('customer_phone', 'Телефон клиента'),

            Sight::make('customer_email', 'Email клиента'),

            Sight::make('products', 'Товары')
                ->render(fn (PurchaseApplication $application) => 
                    $application->products->map(fn ($product) => 
                        '<div class="mb-2">
                            <strong>' . $product->name . '</strong><br>
                            <small class="text-muted">' . number_format($product->price, 2) . ' ₽</small>
                        </div>'
                    )->join('')
                ),

            Sight::make('total_price', 'Общая стоимость')
                ->render(fn (PurchaseApplication $application) => 
                    number_format($application->total_price, 2) . ' ₽'
                ),

            Sight::make('deposit', 'Депозит')
                ->render(fn (PurchaseApplication $application) => 
                    $application->deposit ? number_format($application->deposit, 2) . ' ₽' : '-'
                ),

            Sight::make('status', 'Статус')
                ->render(fn (PurchaseApplication $application) => 
                    '<span class="badge bg-' . $application->status->color() . '">' . $application->status->label() . '</span>'
                ),

            Sight::make('comment', 'Комментарий')
                ->render(fn (PurchaseApplication $application) => 
                    $application->comment ?: '-'
                ),

            Sight::make('created_at', 'Дата создания')
                ->render(fn (PurchaseApplication $application) => $application->created_at->format('d.m.Y H:i')),

            Sight::make('updated_at', 'Дата редактирования')
                ->render(fn (PurchaseApplication $application) => $application->updated_at->format('d.m.Y H:i')),

            Sight::make('approved_at', 'Дата принятия')
                ->render(fn (PurchaseApplication $application) => 
                    $application->approved_at ? $application->approved_at->format('d.m.Y H:i') : '-'
                ),

            Sight::make('canceled_at', 'Дата отмены')
                ->render(fn (PurchaseApplication $application) => 
                    $application->canceled_at ? $application->canceled_at->format('d.m.Y H:i') : '-'
                ),

            Sight::make('completed_at', 'Дата завершения')
                ->render(fn (PurchaseApplication $application) => 
                    $application->completed_at ? $application->completed_at->format('d.m.Y H:i') : '-'
                ),
        ];
    }
}