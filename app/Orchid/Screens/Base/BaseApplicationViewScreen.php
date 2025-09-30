<?php

namespace App\Orchid\Screens\Base;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Models\RentalApplication;
use App\Orchid\Layouts\Base\BaseApplicationViewLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

abstract class BaseApplicationViewScreen extends Screen
{
    public ?RentalApplication $application = null;
    
    protected string $modelClass = RentalApplication::class;
    protected string $editRouteName = 'rental-applications.edit';
    protected string $acceptMessage = 'Заявка на аренду принята';
    protected string $cancelMessage = 'Заявка на аренду отменена';
    protected string $completeMessage = 'Заявка выполнена';

    public function query(RentalApplication $application): iterable
    {
        $this->application = $application;
        
        return [
            'rentalApplication' => $application,
        ];
    }

    public function name(): ?string
    {
        return $this->getApplicationTitle();
    }

    public function commandBar(): iterable
    {
        $commandBar = collect([
            Link::make('Редактировать')
                ->icon('bs.pencil')
                ->route($this->editRouteName, $this->application->id)
        ]);

        if (!$this->application->isCompleted() && !$this->application->isCanceled()) {
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
        $currentStatus = $this->application->status;

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
        $this->application->accept();
        Toast::success($this->acceptMessage);
    }

    public function cancelApplication()
    {
        $this->application->cancel();
        Toast::success($this->cancelMessage);
    }

    public function completeApplication()
    {
        $this->application->complete();
        Toast::success($this->completeMessage);
    }

    public function layout(): iterable
    {
        return [
            $this->getViewLayoutClass(),
        ];
    }

    protected function getApplicationTitle(): string
    {
        return "Заявка №{$this->application->id}";
    }

    abstract protected function getViewLayoutClass(): string;
}