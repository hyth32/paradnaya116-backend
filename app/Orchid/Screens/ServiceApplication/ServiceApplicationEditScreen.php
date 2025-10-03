<?php

namespace App\Orchid\Screens\ServiceApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Http\Requests\Admin\ServiceApplication\SaveServiceApplicationRequest;
use App\Models\Service;
use App\Models\ServiceApplication;
use App\Orchid\Layouts\ServiceApplication\ServiceApplicationEditLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ServiceApplicationEditScreen extends Screen
{
    public ?ServiceApplication $serviceApplication = null;
    
    protected string $createMessage = 'Заявка на услугу добавлена';
    protected string $updateMessage = 'Изменения сохранены';
    
    public function query(ServiceApplication $serviceApplication): iterable
    {
        $this->serviceApplication = $serviceApplication;
        
        return [
            'serviceApplication' => $serviceApplication,
        ];
    }

    public function name(): ?string
    {
        return $this->serviceApplication->exists ? 'Редактирование заявки на услугу' : 'Создание заявки на услугу';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route('service-applications.index'),

            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            ServiceApplicationEditLayout::class,
        ];
    }

    public function save(ServiceApplication $serviceApplication, SaveServiceApplicationRequest $request)
    {
        $data = collect(collect($request->validated())->get('serviceApplication'));

        $serviceIds = collect($data->get('services'), []);
        $totalPrice = Service::whereIn('id', $serviceIds)->sum('price');

        $applicationServices = $serviceIds->toArray();
        $applicationData = collect($data)->except('services')->toArray();

        $status = !$serviceApplication->id
            ? RentalApplicationStatus::New
            : $serviceApplication->status;

        $applicationData['total_price'] = $totalPrice;
        $applicationData['status'] = $status;
        $serviceApplication->fill($applicationData)->save();

        $serviceApplication->services()->sync($applicationServices);

        Toast::success($serviceApplication->wasRecentlyCreated ? $this->createMessage : $this->updateMessage);

        return redirect()->route('service-applications.index', ['status' => $status->value]);
    }
}