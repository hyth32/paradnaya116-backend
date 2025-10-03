<?php

namespace App\Orchid\Layouts\RentalApplication;

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

            TD::make('customer_name', 'Имя клиента'),

            TD::make('start_date', 'Дата начала')
                ->render(fn (RentalApplication $application) => $application->start_date->format('d.m.Y')),

            TD::make('end_date', 'Дата окончания')
                ->render(fn (RentalApplication $application) => $application->end_date->format('d.m.Y')),
            
            TD::make('created_at', 'Дата создания')
                ->render(fn (RentalApplication $application) => $application->created_at->format('d.m.Y H:i')),

            TD::make(__('Actions'))
                ->alignCenter()
                ->render(fn (RentalApplication $application) =>
                    DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Посмотреть')
                                ->icon('bs.eye')
                                ->route('rental-applications.view', $application->id),
                            
                            Link::make('Редактировать')
                                ->canSee(!$application->isCompleted())
                                ->icon('bs.pencil')
                                ->route('rental-applications.edit', $application->id),

                            Button::make('Удалить')
                                ->icon('bs.trash')
                                ->canSee(!$application->trashed())
                                ->confirm('Вы уверены, что хотите удалить заявку?')
                                ->method('remove', [
                                    'id' => $application->id,
                                ]),
                        ]),
                ),
        ];
    }
}
