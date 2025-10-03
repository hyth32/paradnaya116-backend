<?php

namespace App\Orchid\Screens\Service;

use App\Models\Service;
use App\Orchid\Layouts\Service\ServiceStatusBlockLayout;
use App\Orchid\Layouts\Service\ServiceViewLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ServiceViewScreen extends Screen
{
    public ?Service $service = null;

    public function query(Service $service): iterable
    {
        return [
            'service' => $service,
        ];
    }

    public function name(): ?string
    {
        return $this->service->name;
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route('services.index'),

            Link::make('Редактировать')
                ->icon('bs.pencil')
                ->route('services.edit', $this->service->id),

            Button::make($this->service->isArchived() ? 'Восстановить из архива' : 'Архивировать')
                ->canSee(!$this->service->isTrashed())
                ->icon($this->service->isArchived() ? 'bs.arrow-bar-up' : 'bs.archive')
                ->confirm($this->service->isArchived()
                    ? 'Вы уверены, что хотите восстановить услугу из архива?'
                    : 'Вы уверены, что хотите переместить услугу в архив?'
                )
                ->method('toggleArchive', [
                    'id' => $this->service->id,
                ]),

            Button::make($this->service->isTrashed() ? 'Восстановить' : 'Удалить')
                ->icon($this->service->isTrashed() ? 'bs.arrow-counterclockwise' : 'bs.trash')
                ->confirm($this->service->isTrashed()
                    ? 'Вы уверены, что хотите восстановить услугу?'
                    : 'Вы уверены, что хотите удалить услугу?'
                )
                ->method('toggleRemove', [
                    'id' => $this->service->id,
                ]),
        ];
    }

    public function toggleArchive(int $id): void
    {
        $service = Service::findOrFail($id);

        if ($service->isArchived()) {
            $service->status = \App\Enums\Service\ServiceStatus::Active;
            $service->save();
            Toast::success('Услуга восстановлена из архива');
        } else {
            $service->status = \App\Enums\Service\ServiceStatus::Archived;
            $service->save();
            Toast::success('Услуга перенесена в архив');
        }
    }

    public function toggleRemove(int $id): void
    {
        $service = Service::findOrFail($id);

        if ($service->isTrashed()) {
            $service->status = \App\Enums\Service\ServiceStatus::Active;
            $service->save();
            Toast::success('Услуга восстановлена');
        } else {
            $service->status = \App\Enums\Service\ServiceStatus::Trashed;
            $service->save();
            Toast::success('Услуга удалена');
        }
    }

    public function layout(): iterable
    {
        return [
            ServiceStatusBlockLayout::class,
            ServiceViewLayout::class,
        ];
    }
}