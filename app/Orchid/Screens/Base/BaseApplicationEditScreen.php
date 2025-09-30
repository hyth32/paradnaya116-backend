<?php

namespace App\Orchid\Screens\Base;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Http\Requests\Admin\RentalApplication\SaveRentalApplicationRequest;
use App\Models\Product;
use App\Models\RentalApplication;
use App\Orchid\Layouts\Base\BaseApplicationEditLayout;
use Orchid\Screen\Actions\Button;
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
        $totalPrice = Product::whereIn('id', $productIds)->sum('price');

        $applicationProducts = $productIds
            ->map(fn ($productId) => ['product_id' => $productId, 'quantity' => 1])
            ->toArray();
        $applicationData = collect($data)->except('products')->toArray();

        $status = !$application->id
            ? RentalApplicationStatus::New
            : $application->status;

        $applicationData['total_price'] = $totalPrice;
        $applicationData['status'] = $status;
        $application->fill($applicationData)->save();

        $application->products()->sync($applicationProducts);

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

    abstract protected function getEditLayoutClass(): string;
}