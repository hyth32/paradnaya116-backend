<?php

namespace App\Orchid\Screens\ServiceApplication;

use App\Models\ServiceApplication;
use App\Orchid\Layouts\ServiceApplication\ServiceApplicationViewLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ServiceApplicationViewScreen extends Screen
{
    public ?ServiceApplication $serviceApplication = null;

    public function query(ServiceApplication $serviceApplication): iterable
    {
        $serviceApplication->load(['services']);
        
        return [
            'serviceApplication' => $serviceApplication,
        ];
    }

    public function name(): ?string
    {
        return "Заявка на услугу №{$this->serviceApplication->id}";
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route('service-applications.index'),

            Link::make('Редактировать')
                ->icon('bs.pencil')
                ->route('service-applications.edit', $this->serviceApplication->id),

            Button::make('Принять')
                ->canSee($this->serviceApplication->isNew())
                ->icon('bs.check-circle')
                ->confirm('Вы уверены, что хотите принять заявку?')
                ->method('accept', [
                    'id' => $this->serviceApplication->id,
                ]),

            Button::make('Отменить')
                ->canSee($this->serviceApplication->isActive())
                ->icon('bs.x-circle')
                ->confirm('Вы уверены, что хотите отменить заявку?')
                ->method('cancel', [
                    'id' => $this->serviceApplication->id,
                ]),

            Button::make('Завершить')
                ->canSee($this->serviceApplication->isActive())
                ->icon('bs.check-circle-fill')
                ->confirm('Вы уверены, что хотите завершить заявку?')
                ->method('complete', [
                    'id' => $this->serviceApplication->id,
                ]),
        ];
    }

    public function accept(int $id): void
    {
        $application = ServiceApplication::findOrFail($id);
        $application->accept();
        Toast::success('Заявка принята');
    }

    public function cancel(int $id): void
    {
        $application = ServiceApplication::findOrFail($id);
        $application->cancel();
        Toast::success('Заявка отменена');
    }

    public function complete(int $id): void
    {
        $application = ServiceApplication::findOrFail($id);
        $application->complete();
        Toast::success('Заявка завершена');
    }

    public function layout(): iterable
    {
        return [
            ServiceApplicationViewLayout::class,
        ];
    }
}