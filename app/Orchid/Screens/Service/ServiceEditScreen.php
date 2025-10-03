<?php

namespace App\Orchid\Screens\Service;

use App\Http\Requests\Admin\Service\SaveServiceRequest;
use App\Models\Service;
use App\Orchid\Layouts\Service\ServiceEditLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ServiceEditScreen extends Screen
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
        return $this->service->exists ? 'Редактирование услуги' : 'Создание услуги';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route('services.index'),

            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            ServiceEditLayout::class,
        ];
    }

    public function save(Service $service, SaveServiceRequest $request)
    {
        $data = collect($request->validated())->get('service');

        $service->fill($data)->save();

        Toast::success($service->wasRecentlyCreated ? 'Услуга добавлена' : 'Изменения сохранены');

        $status = $service->status;

        return redirect()->route('services.index', ['status' => $status->value]);
    }
}