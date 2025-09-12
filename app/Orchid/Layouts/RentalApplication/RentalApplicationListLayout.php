<?php

namespace App\Orchid\Layouts\RentalApplication;

use App\Models\Product;
use App\Models\RentalApplication;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class RentalApplicationListLayout extends Table
{
    protected $target = 'rentalApplications';

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID'),

            TD::make('customer_name', 'Имя арендатора'),

            TD::make('start_date', 'Начало аренды')
                ->render(fn (RentalApplication $rentalApplication) => $rentalApplication->start_date->format('d.m.Y')),

            TD::make('end_date', 'Конец аренды')
                ->render(fn (RentalApplication $rentalApplication) => $rentalApplication->end_date->format('d.m.Y')),
            
            TD::make('created_at', 'Дата создания')
                ->render(fn (RentalApplication $rentalApplication) => $rentalApplication->created_at->format('d.m.Y H:i')),

            TD::make(__('Actions'))
                ->alignCenter()
                ->render(fn (RentalApplication $rentalApplication) =>
                    DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            // Link::make('Посмотреть')
                            //     ->icon('bs.eye')
                            //     ->route('products.view', $product->id),
                            
                            Link::make('Редактировать')
                                ->icon('bs.pencil')
                                ->route('rental-applications.edit', $rentalApplication->id),

                            Button::make('Удалить')
                                ->icon('bs.trash')
                                ->canSee(!$rentalApplication->trashed())
                                ->confirm('Вы уверены, что хотите удалить заявку?')
                                ->method('remove', [
                                    'id' => $rentalApplication->id,
                                ]),
                        ]),
                ),
        ];
    }
}
