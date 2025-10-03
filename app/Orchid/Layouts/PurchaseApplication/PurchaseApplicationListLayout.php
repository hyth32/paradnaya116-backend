<?php

namespace App\Orchid\Layouts\PurchaseApplication;

use App\Models\PurchaseApplication;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class PurchaseApplicationListLayout extends Table
{
    protected $target = 'purchaseApplications';

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID'),

            TD::make('customer_name', 'Имя клиента'),

            TD::make('customer_phone', 'Телефон клиента'),

            TD::make('products', 'Товары')
                ->render(fn (PurchaseApplication $application) => 
                    $application->products->pluck('name')->join(', ')
                ),

            TD::make('total_price', 'Общая стоимость')
                ->render(fn (PurchaseApplication $application) => 
                    number_format($application->total_price, 2) . ' ₽'
                ),

            TD::make('status', 'Статус')
                ->render(fn (PurchaseApplication $application) => 
                    '<span class="badge bg-' . $application->status->color() . '">' . $application->status->label() . '</span>'
                ),

            TD::make('created_at', 'Дата создания')
                ->render(fn (PurchaseApplication $application) => $application->created_at->format('d.m.Y H:i')),

            TD::make(__('Actions'))
                ->alignCenter()
                ->render(fn (PurchaseApplication $application) =>
                    DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Посмотреть')
                                ->icon('bs.eye')
                                ->route('purchase-applications.view', $application->id),
                            
                            Link::make('Редактировать')
                                ->icon('bs.pencil')
                                ->route('purchase-applications.edit', $application->id),

                            Button::make('Принять')
                                ->canSee($application->isNew())
                                ->icon('bs.check-circle')
                                ->confirm('Вы уверены, что хотите принять заявку?')
                                ->method('accept', [
                                    'id' => $application->id,
                                ]),

                            Button::make('Отменить')
                                ->canSee($application->isActive())
                                ->icon('bs.x-circle')
                                ->confirm('Вы уверены, что хотите отменить заявку?')
                                ->method('cancel', [
                                    'id' => $application->id,
                                ]),

                            Button::make('Завершить')
                                ->canSee($application->isActive())
                                ->icon('bs.check-circle-fill')
                                ->confirm('Вы уверены, что хотите завершить заявку?')
                                ->method('complete', [
                                    'id' => $application->id,
                                ]),
                        ]),
                ),
        ];
    }
}