<?php

namespace App\Orchid\Screens\RentalApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Http\Requests\Admin\RentalApplication\SaveRentalApplicationRequest;
use App\Models\Product;
use App\Orchid\Layouts\RentalApplication\RentalApplicatonEditLayout;
use App\Models\RentalApplication;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class RentalApplicationEditScreen extends Screen
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
        return $this->rentalApplication->exists ? 'Редактирование заявки на аренду' : 'Создание заявки на аренду';
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
            RentalApplicatonEditLayout::class,
        ];
    }

    public function save(RentalApplication $rentalApplication, SaveRentalApplicationRequest $request)
    {
        $data = collect(collect($request->validated())->get('rentalApplication'));

        $productIds = collect($data->get('products'), []);
        $totalPrice = Product::whereIn('id', $productIds)->sum('price');

        $rentalApplicationProducts = $productIds
            ->map(fn ($productId) => ['product_id' => $productId, 'quantity' => 1])
            ->toArray();
        $rentalApplicationData = collect($data)->except('products')->toArray();

        $status = !$rentalApplication->id
            ? RentalApplicationStatus::New
            : $rentalApplication->status;

        $rentalApplicationData['total_price'] = $totalPrice;
        $rentalApplicationData['status'] = $status;
        $rentalApplication->fill($rentalApplicationData)->save();

        $rentalApplication->products()->sync($rentalApplicationProducts);

        Toast::success($rentalApplication->wasRecentlyCreated ? 'Заявка на аренду добавлена' : 'Изменения сохранены');

        return redirect()->route('rental-applications.index', ['status' => $status->value]);
    }
}
