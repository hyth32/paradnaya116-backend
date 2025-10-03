<?php

namespace App\Orchid\Screens\Service;

use App\Enums\Service\ServiceStatus;
use App\Models\Service;
use App\Orchid\Layouts\Service\ServiceListLayout;
use App\Orchid\Layouts\Service\ServiceListTabMenu;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ServiceListScreen extends Screen
{
    public function query(): iterable
    {
        $status = request()->get('status', ServiceStatus::Active->value);

        $query = Service::defaultSort('id', 'desc');

        match ($status) {
            ServiceStatus::Archived->value => $query->archived(),
            ServiceStatus::Trashed->value => $query->trashed(),
            default => $query->active(),
        };

        return [
            'services' => $query->paginate(),
            'status' => $status,
        ];
    }

    public function name(): ?string
    {
        return 'Каталог услуг';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->route('services.create'),
        ];
    }

    public function layout(): iterable
    {
        return [
            ServiceListTabMenu::class,
            ServiceListLayout::class,
        ];
    }

    public function toggleArchive(int $id): void
    {
        $service = Service::findOrFail($id);

        if ($service->isArchived()) {
            $service->status = ServiceStatus::Active;
            $service->save();
            Toast::success('Услуга восстановлена из архива');
        } else {
            $service->status = ServiceStatus::Archived;
            $service->save();
            Toast::success('Услуга перенесена в архив');
        }
    }

    public function toggleRemove(int $id): void
    {
        $service = Service::findOrFail($id);

        if ($service->isTrashed()) {
            $service->status = ServiceStatus::Active;
            $service->save();
            Toast::success('Услуга восстановлена');
        } else {
            $service->status = ServiceStatus::Trashed;
            $service->save();
            Toast::success('Услуга удалена');
        }
    }
}