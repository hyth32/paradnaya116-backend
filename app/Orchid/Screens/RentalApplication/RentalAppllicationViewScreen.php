<?php

namespace App\Orchid\Screens\RentalApplication;

use App\Models\RentalApplication;
use App\Orchid\Layouts\RentalApplication\RentalApplicationStatusBlockLayout;
use App\Orchid\Layouts\RentalApplication\RentalApplicationViewLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class RentalAppllicationViewScreen extends Screen
{
    public ?RentalApplication $rentalApplication = null;

    public function query(RentalApplication $rentalApplication): iterable
    {
        $rentalApplication->load(['products']);
        
        return [
            'rentalApplication' => $rentalApplication,
        ];
    }

    public function name(): ?string
    {
        return "Заявка на аренду №{$this->rentalApplication->id}";
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route('rental-applications.index'),

            Link::make('Редактировать')
                ->icon('bs.pencil')
                ->route('rental-applications.edit', $this->rentalApplication->id),

            Button::make('Принять')
                ->canSee($this->rentalApplication->isNew())
                ->icon('bs.check-circle')
                ->confirm('Вы уверены, что хотите принять заявку?')
                ->method('accept', [
                    'id' => $this->rentalApplication->id,
                ]),

            Button::make('Отменить')
                ->canSee($this->rentalApplication->isActive())
                ->icon('bs.x-circle')
                ->confirm('Вы уверены, что хотите отменить заявку?')
                ->method('cancel', [
                    'id' => $this->rentalApplication->id,
                ]),

            Button::make('Завершить')
                ->canSee($this->rentalApplication->isActive())
                ->icon('bs.check-circle-fill')
                ->confirm('Вы уверены, что хотите завершить заявку?')
                ->method('complete', [
                    'id' => $this->rentalApplication->id,
                ]),
        ];
    }

    public function accept(int $id): void
    {
        $application = RentalApplication::findOrFail($id);
        $application->accept();
        Toast::success('Заявка принята');
    }

    public function cancel(int $id): void
    {
        $application = RentalApplication::findOrFail($id);
        $application->cancel();
        Toast::success('Заявка отменена');
    }

    public function complete(int $id): void
    {
        $application = RentalApplication::findOrFail($id);
        $application->complete();
        Toast::success('Заявка завершена');
    }

    public function layout(): iterable
    {
        return [
            RentalApplicationStatusBlockLayout::class,
            RentalApplicationViewLayout::class,
        ];
    }
}
