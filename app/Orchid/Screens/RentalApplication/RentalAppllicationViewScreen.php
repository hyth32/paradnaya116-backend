<?php

namespace App\Orchid\Screens\RentalApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Models\RentalApplication;
use App\Models\RentalApplicationProduct;
use App\Orchid\Layouts\Product\ProductStatusBlockLayout;
use App\Orchid\Layouts\Product\ProductViewLayout;
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
        return [
            DropDown::make('Изменить статус')
                ->icon('bs.clipboard')
                ->list($this->getStatusButtons()),

            Link::make('Редактировать')
                ->icon('bs.pencil')
                ->route('rental-applications.edit', $this->rentalApplication->id),
        ];
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

        if (!in_array($currentStatus, [RentalApplicationStatus::Canceled, RentalApplicationStatus::Completed])) {
            $statusButtons->push(Button::make('Отменить')->method('cancelApplication'));
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

    public function layout(): iterable
    {
        return [
            // ProductStatusBlockLayout::class,
            // ProductViewLayout::class,
        ];
    }
}
