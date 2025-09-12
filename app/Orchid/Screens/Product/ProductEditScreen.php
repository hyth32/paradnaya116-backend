<?php

namespace App\Orchid\Screens\Product;

use App\Enums\Product\ProductStatus;
use App\Http\Requests\Admin\Product\SaveProductRequest;
use App\Models\Product;
use App\Orchid\Layouts\Product\ProductEditLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class ProductEditScreen extends Screen
{
    public ?Product $product = null;
    
    public function query(Product $product): iterable
    {
        $product->load('images');

        return [
            'product' => $product,
        ];
    }

    public function name(): ?string
    {
        return $this->product->exists ? 'Редактирование товара' : 'Создание товара';
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
            ProductEditLayout::class,
        ];
    }

    public function save(Product $product, SaveProductRequest $request)
    {
        $data = collect($request->validated())->get('product');

        $product->fill($data)->save();

        Toast::success($product->wasRecentlyCreated ? 'Товар добавлен' : 'Изменения сохранены');

        $status = ProductStatus::Active;
        if ($product->trashed()) {
            $status = ProductStatus::Trashed;
        }

        if ($product->isArchived()) {
            $status = ProductStatus::Archived;
        }

        return redirect()->route('products.index', ['status' => $status->value]);
    }
}
