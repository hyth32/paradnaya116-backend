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
        $commandBar = collect([
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
        $currentStatus = $this->rentalApplication->status;

        $statusButtons = collect();

        if ($currentStatus == RentalApplicationStatus::New) {
            $statusButtons->push(
                Button::make('Принять в работу')->method('acceptApplication')
            );
        }

        if ($currentStatus == RentalApplicationStatus::Active) {
            $statusButtons->push(
                Button::make('Отметить как выполненную')->method('completeApplication')
            );
        }

        if (!in_array($currentStatus, [RentalApplicationStatus::Canceled, RentalApplicationStatus::Completed])) {
            $statusButtons->push(
                Button::make('Отменить')->method('cancelApplication'),
            );
        }

        return $statusButtons->toArray();
    }

    public function acceptApplication()
    {
        $this->rentalApplication->accept();
        Toast::success('Заявка на аренду принята');
    }

    public function cancelApplication()
    {
        $this->rentalApplication->cancel();
        Toast::success('Заявка на аренду отменена');
    }

    public function completeApplication()
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
