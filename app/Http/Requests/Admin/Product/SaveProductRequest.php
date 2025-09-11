<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class SaveProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product.name' => 'required|string',
            'product.description' => 'nullable|string',
            'product.price' => 'required|decimal:0,2|min:1',
            'product.quantity' => 'required|integer|min:1',
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
