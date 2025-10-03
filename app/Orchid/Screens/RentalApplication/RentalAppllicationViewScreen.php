<?php

namespace App\Orchid\Screens\RentalApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Models\RentalApplication;
use App\Orchid\Layouts\RentalApplication\RentalApplicationStatusBlockLayout;
use App\Orchid\Layouts\RentalApplication\RentalApplicationViewLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class RentalAppllicationViewScreen extends Screen
{
    public ?RentalApplication $rentalApplication = null;

    public function query(RentalApplication $rentalApplication): iterable
    {
        $this->rentalApplication = $rentalApplication;
        
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
        if (!$this->rentalApplication) {
            return [];
        }

        $commandBar = collect([
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route('rental-applications.index'),
                
            Link::make('Редактировать')
                ->icon('bs.pencil')
                ->route('rental-applications.edit', $this->rentalApplication->id)
        ]);

        if (!$this->rentalApplication->isCompleted() && !$this->rentalApplication->isCanceled()) {
            $commandBar->push(
                DropDown::make('Изменить статус')
                    ->icon('bs.clipboard')
                    ->list($this->getStatusButtons()),
            );
        }

        return $commandBar;
    }

    protected function getStatusButtons(): array
    {
        if (!$this->rentalApplication) {
            return [];
        }

        $currentStatus = $this->rentalApplication->status;

        $statusButtons = collect();

        if ($currentStatus == RentalApplicationStatus::New) {
            $statusButtons->push(
                Button::make('Принять в работу')->method('accept')
            );
        }

        if ($currentStatus == RentalApplicationStatus::Active) {
            $statusButtons->push(
                Button::make('Отметить как выполненную')->method('complete')
            );
        }

        if (!in_array($currentStatus, [RentalApplicationStatus::Canceled, RentalApplicationStatus::Completed])) {
            $statusButtons->push(
                Button::make('Отменить')->method('cancel'),
            );
        }

        return $statusButtons->toArray();
    }

    public function accept()
    {
        $this->rentalApplication->accept();
        Toast::success('Заявка принята');
    }

    public function cancel()
    {
        $this->rentalApplication->cancel();
        Toast::success('Заявка отменена');
    }

    public function complete()
    {
        $this->rentalApplication->complete();
        Toast::success('Заявка выполнена');
    }

    public function layout(): iterable
    {
        return [
            RentalApplicationStatusBlockLayout::class,
            RentalApplicationViewLayout::class,
        ];
    }
}
