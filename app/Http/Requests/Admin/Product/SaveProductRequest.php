<?php

namespace App\Http\Requests\Admin\Product;

use App\Enums\Product\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product.name' => 'required|string',
            'product.description' => 'nullable|string',
            'product.price' => 'required|decimal:0,2|min:1',
            'product.quantity' => 'required|integer|min:1',
            'product.status' => ['required', Rule::enum(ProductStatus::class)]
        ];
    }

    public function attributes(): array
    {
        return [
            'product.name' => 'Название',
            'product.description' => 'Описание',
            'product.price' => 'Стоимость',
            'product.quantity' => 'Количество',
        ];
    }
}
