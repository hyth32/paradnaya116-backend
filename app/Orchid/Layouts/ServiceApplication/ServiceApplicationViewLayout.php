<?php

namespace App\Orchid\Layouts\ServiceApplication;

use App\Models\ServiceApplication;
use Orchid\Screen\Layouts\Legend;
use Orchid\Screen\Sight;

class ServiceApplicationViewLayout extends Legend
{
    protected $target = 'serviceApplication';

    protected function columns(): iterable
    {
        return [
            Sight::make('id', 'ID'),

            Sight::make('customer_name', 'Имя клиента'),

            Sight::make('customer_phone', 'Телефон клиента'),

            Sight::make('customer_email', 'Email клиента'),

            Sight::make('services', 'Услуги')
                ->render(fn (ServiceApplication $application) => 
                    $application->services->map(fn ($service) => 
                        '<div class="mb-2">
                            <strong>' . $service->name . '</strong><br>
                            <small class="text-muted">' . number_format($service->price, 2) . ' ₽</small>
                        </div>'
                    )->join('')
                ),

            Sight::make('total_price', 'Общая стоимость')
                ->render(fn (ServiceApplication $application) => 
                    number_format($application->total_price, 2) . ' ₽'
                ),

            Sight::make('deposit', 'Депозит')
                ->render(fn (ServiceApplication $application) => 
                    $application->deposit ? number_format($application->deposit, 2) . ' ₽' : '-'
                ),

            Sight::make('status', 'Статус')
                ->render(fn (ServiceApplication $application) => 
                    '<span class="badge bg-' . $application->status->color() . '">' . $application->status->label() . '</span>'
                ),

            Sight::make('start_date', 'Дата начала')
                ->render(fn (ServiceApplication $application) => 
                    $application->start_date ? $application->start_date->format('d.m.Y H:i') : '-'
                ),

            Sight::make('end_date', 'Дата окончания')
                ->render(fn (ServiceApplication $application) => 
                    $application->end_date ? $application->end_date->format('d.m.Y H:i') : '-'
                ),

            Sight::make('comment', 'Комментарий')
                ->render(fn (ServiceApplication $application) => 
                    $application->comment ?: '-'
                ),

            Sight::make('created_at', 'Дата создания')
                ->render(fn (ServiceApplication $application) => $application->created_at->format('d.m.Y H:i')),

            Sight::make('updated_at', 'Дата редактирования')
                ->render(fn (ServiceApplication $application) => $application->updated_at->format('d.m.Y H:i')),

            Sight::make('approved_at', 'Дата принятия')
                ->render(fn (ServiceApplication $application) => 
                    $application->approved_at ? $application->approved_at->format('d.m.Y H:i') : '-'
                ),

            Sight::make('canceled_at', 'Дата отмены')
                ->render(fn (ServiceApplication $application) => 
                    $application->canceled_at ? $application->canceled_at->format('d.m.Y H:i') : '-'
                ),

            Sight::make('completed_at', 'Дата завершения')
                ->render(fn (ServiceApplication $application) => 
                    $application->completed_at ? $application->completed_at->format('d.m.Y H:i') : '-'
                ),
        ];
    }
}