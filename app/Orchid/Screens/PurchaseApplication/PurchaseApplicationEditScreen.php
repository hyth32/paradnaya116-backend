<?php

namespace App\Orchid\Screens\PurchaseApplication;

use App\Enums\RentalApplication\RentalApplicationStatus;
use App\Http\Requests\Admin\PurchaseApplication\SavePurchaseApplicationRequest;
use App\Models\Product;
use App\Models\PurchaseApplication;
use App\Orchid\Layouts\PurchaseApplication\PurchaseApplicationEditLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class PurchaseApplicationEditScreen extends Screen
{
    public ?PurchaseApplication $purchaseApplication = null;
    
    protected string $createMessage = 'Заявка на покупку добавлена';
    protected string $updateMessage = 'Изменения сохранены';
    
    public function query(PurchaseApplication $purchaseApplication): iterable
    {
        $this->purchaseApplication = $purchaseApplication;
        
        return [
            'purchaseApplication' => $purchaseApplication,
        ];
    }

    public function name(): ?string
    {
        return $this->purchaseApplication->exists ? 'Редактирование заявки на покупку' : 'Создание заявки на покупку';
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Назад')
                ->icon('bs.arrow-left')
                ->route('purchase-applications.index'),

            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    public function layout(): iterable
    {
        return [
            PurchaseApplicationEditLayout::class,
        ];
    }

    public function save(PurchaseApplication $purchaseApplication, SavePurchaseApplicationRequest $request)
    {
        $data = collect(collect($request->validated())->get('purchaseApplication'));

        $productIds = collect($data->get('products'), []);
        $totalPrice = Product::whereIn('id', $productIds)->sum('price');

        $applicationProducts = $productIds
            ->map(fn ($productId) => ['product_id' => $productId, 'quantity' => 1])
            ->toArray();
        $applicationData = collect($data)->except('products')->toArray();

        $status = !$purchaseApplication->id
            ? RentalApplicationStatus::New
            : $purchaseApplication->status;

        $applicationData['total_price'] = $totalPrice;
        $applicationData['status'] = $status;
        $purchaseApplication->fill($applicationData)->save();

        $purchaseApplication->products()->sync($applicationProducts);

        Toast::success($purchaseApplication->wasRecentlyCreated ? $this->createMessage : $this->updateMessage);

        return redirect()->route('purchase-applications.index', ['status' => $status->value]);
    }
}