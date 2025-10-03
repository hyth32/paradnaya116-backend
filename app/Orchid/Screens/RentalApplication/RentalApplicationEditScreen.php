<?php

namespace App\Orchid\Screens\RentalApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Enums\RentalApplication\RentalApplicationType;
use App\Http\Requests\Admin\RentalApplication\SaveRentalApplicationRequest;
use App\Models\Product;
use App\Models\RentalApplication;
use App\Orchid\Screens\Base\BaseApplicationEditScreen;
use App\Orchid\Layouts\RentalApplication\RentalApplicatonEditLayout;
use Orchid\Support\Facades\Toast;

class RentalApplicationEditScreen extends BaseApplicationEditScreen
{
    protected string $createMessage = 'Заявка на аренду добавлена';
    protected string $updateMessage = 'Изменения сохранены';

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

        return redirect()->route('rental-applications.index', ['status' => $status->value]);
    }

    protected function getEditLayoutClass(): string
    {
        return RentalApplicatonEditLayout::class;
    }

    protected function getBackRoute(): string
    {
        return 'rental-applications.index';
    }
}
