<?php

namespace App\Orchid\Layouts\Service;

use App\Models\Service;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ServiceListLayout extends Table
{
    protected $target = 'services';

    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID'),

            TD::make('name', 'Название услуги'),

            TD::make('price', 'Стоимость услуги')
                ->render(fn (Service $service) => number_format($service->price, 2) . ' ₽'),

            TD::make('status', 'Статус')
                ->render(fn (Service $service) => 
                    '<span class="badge bg-' . $service->status->color() . '">' . $service->status->label() . '</span>'
                ),

            TD::make('created_at', 'Дата создания')
                ->render(fn (Service $service) => $service->created_at->format('d.m.Y H:i')),

            TD::make('updated_at', 'Дата редактирования')
                ->render(fn (Service $service) => $service->updated_at->format('d.m.Y H:i')),

            TD::make(__('Actions'))
                ->alignCenter()
                ->render(fn (Service $service) =>
                    DropDown::make()
                        ->icon('bs.three-dots-vertical')
                        ->list([
                            Link::make('Посмотреть')
                                ->icon('bs.eye')
                                ->route('services.view', $service->id),
                            
                            Link::make('Редактировать')
                                ->icon('bs.pencil')
                                ->route('services.edit', $service->id),

                            Button::make($service->isArchived() ? 'Восстановить из архива' : 'Архивировать')
                                ->canSee(!$service->isTrashed())
                                ->icon($service->isArchived() ? 'bs.arrow-bar-up' : 'bs.archive')
                                ->confirm($service->isArchived()
                                    ? 'Вы уверены, что хотите восстановить услугу из архива?'
                                    : 'Вы уверены, что хотите переместить услугу в архив?'
                                )
                                ->method('toggleArchive', [
                                    'id' => $service->id,
                                ]),

                            Button::make($service->isTrashed() ? 'Восстановить' : 'Удалить')
                                ->icon($service->isTrashed() ? 'bs.arrow-counterclockwise' : 'bs.trash')
                                ->confirm($service->isTrashed()
                                    ? 'Вы уверены, что хотите восстановить услугу?'
                                    : 'Вы уверены, что хотите удалить услугу?'
                                )
                                ->method('toggleRemove', [
                                    'id' => $service->id,
                                ]),
                        ]),
                ),
        ];
    }
}