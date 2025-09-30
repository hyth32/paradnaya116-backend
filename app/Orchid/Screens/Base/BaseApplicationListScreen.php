<?php

namespace App\Orchid\Screens\Base;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Models\RentalApplication;
use App\Orchid\Layouts\Base\BaseApplicationListLayout;
use App\Orchid\Layouts\Base\BaseApplicationListTabLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

abstract class BaseApplicationListScreen extends Screen
{
    protected string $modelClass = RentalApplication::class;
    protected string $routePrefix = 'rental-applications';
    protected string $screenName = 'Список заявок';
    protected string $createRouteName = 'rental-applications.create';
    protected string $deleteMessage = 'Заявка на аренду удалена';

    public function query(): iterable
    {
        $status = request()->get('status', RentalApplicationStatus::New->value);

        $query = $this->modelClass::defaultSort('id', 'desc');

        match ($status) {
            RentalApplicationStatus::Active->value => $query->active(),
            RentalApplicationStatus::Canceled->value => $query->canceled(),
            RentalApplicationStatus::Completed->value => $query->completed(),
            default => $query->new(),
        };

        return [
            'rentalApplications' => $query->paginate(),
            'status' => $status,
        ];
    }

    public function name(): ?string
    {
        return $this->screenName;
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->route($this->createRouteName),
        ];
    }

    public function layout(): iterable
    {
        return [
            $this->getTabLayoutClass(),
            $this->getListLayoutClass(),
        ];
    }

    public function remove(int $id): void
    {
        $application = $this->modelClass::findOrFail($id);   
        $application->delete();
        Toast::success($this->deleteMessage);
    }

    abstract protected function getTabLayoutClass(): string;

    abstract protected function getListLayoutClass(): string;
}