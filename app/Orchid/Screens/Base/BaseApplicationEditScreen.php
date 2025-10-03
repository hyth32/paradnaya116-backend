<?php

namespace App\Orchid\Screens\Base;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Http\Requests\Admin\RentalApplication\SaveRentalApplicationRequest;
use App\Models\Product;
use App\Models\Service;
use App\Models\RentalApplication;
use App\Orchid\Layouts\Base\BaseApplicationEditLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

abstract class BaseApplicationEditScreen extends Screen
{
    public ?RentalApplication $application = null;
    
    protected string $modelClass = RentalApplication::class;
    protected string $requestClass = SaveRentalApplicationRequest::class;
    protected string $listRouteName = 'rental-applications.index';
    protected string $createMessage = 'Заявка на аренду добавлена';
    protected string $updateMessage = 'Изменения сохранены';
    
    public function query(RentalApplication $application): iterable
    {
        $this->application = $application;
        
        return [
            'rentalApplication' => $application,
        ];
    }

    public function name(): ?string
    {
        return $this->application->exists ? $this->getEditTitle() : $this->getCreateTitle();
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route($this->getBackRoute()),

            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            $this->getEditLayoutClass(),
        ];
    }

    public function save(RentalApplication $application, SaveRentalApplicationRequest $request)
    {
        $data = collect(collect($request->validated())->get('rentalApplication'));

        $productIds = collect($data->get('products'), []);
        $serviceIds = collect($data->get('services'), []);
        
        $totalPrice = 0;
        if ($productIds->isNotEmpty()) {
            $totalPrice += Product::whereIn('id', $productIds)->sum('price');
        }
        if ($serviceIds->isNotEmpty()) {
            $totalPrice += Service::whereIn('id', $serviceIds)->sum('price');
        }

        $applicationProducts = $productIds
            ->map(fn ($productId) => ['product_id' => $productId, 'quantity' => 1])
            ->toArray();
        $applicationServices = $serviceIds->toArray();
        
        $applicationData = collect($data)->except(['products', 'services'])->toArray();

        $status = !$application->id
            ? RentalApplicationStatus::New
            : $application->status;

        $applicationData['total_price'] = $totalPrice;
        $applicationData['status'] = $status;
        $application->fill($applicationData)->save();

        if ($productIds->isNotEmpty()) {
            $application->products()->sync($applicationProducts);
        }
        if ($serviceIds->isNotEmpty()) {
            $application->services()->sync($applicationServices);
        }

        Toast::success($application->wasRecentlyCreated ? $this->createMessage : $this->updateMessage);

        return redirect()->route($this->listRouteName, ['status' => $status->value]);
    }

    protected function getCreateTitle(): string
    {
        return 'Создание заявки';
    }

    protected function getEditTitle(): string
    {
        return 'Редактирование заявки';
    }

    protected function getBackRoute(): string
    {
        return 'rental-applications.index';
    }

    abstract protected function getEditLayoutClass(): string;
}