<?php

namespace App\Orchid\Screens\RentalApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Http\Requests\Admin\RentalApplication\SaveRentalApplicationRequest;
use App\Models\Product;
use App\Models\RentalApplication;
use App\Orchid\Layouts\RentalApplication\RentalApplicatonEditLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class RentalApplicationEditScreen extends Screen
{
    public ?RentalApplication $rentalApplication = null;

    protected string $createMessage = 'Заявка на аренду добавлена';
    protected string $updateMessage = 'Изменения сохранены';

    public function query(RentalApplication $rentalApplication): iterable
    {
        $this->rentalApplication = $rentalApplication;
        
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
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route('rental-applications.index'),

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

    public function save(RentalApplication $application, SaveRentalApplicationRequest $request)
    {
        $data = collect(collect($request->validated())->get('rentalApplication'));

        $productIds = collect($data->get('products'), []);
        
        $totalPrice = 0;
        if ($productIds->isNotEmpty()) {
            $totalPrice += Product::whereIn('id', $productIds)->sum('price');
        }

        $applicationProducts = $productIds
            ->map(fn ($productId) => ['product_id' => $productId, 'quantity' => 1])
            ->toArray();
        
        $applicationData = collect($data)->except(['products'])->toArray();

        $status = !$application->id
            ? RentalApplicationStatus::New
            : $application->status;

        $applicationData['total_price'] = $totalPrice;
        $applicationData['status'] = $status;
        $application->fill($applicationData)->save();

        if ($productIds->isNotEmpty()) {
            $application->products()->sync($applicationProducts);
        }

        Toast::success($application->wasRecentlyCreated ? 'Заявка на аренду добавлена' : 'Изменения сохранены');

        return redirect()->route('rental-applications.index', ['status' => $status->value]);
    }
}
